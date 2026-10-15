<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\LegalAidProvider;
use App\Models\AdminActivityLog;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = LegalAidProvider::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('location', 'like', "%{$q}%")
                  ->orWhere('district', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        if ($request->input('missing') === 'contact') {
            $query->whereNull('phone')->whereNull('email');
        } elseif ($request->input('missing') === 'district') {
            $query->whereNull('district');
        }

        $providers = $query->orderBy('region')->orderBy('name')->paginate(25)->withQueryString();

        $regions = LegalAidProvider::select('region')->whereNotNull('region')->distinct()->orderBy('region')->pluck('region');
        $counts = [
            'all'        => LegalAidProvider::count(),
            'no_contact' => LegalAidProvider::whereNull('phone')->whereNull('email')->count(),
            'no_district'=> LegalAidProvider::whereNull('district')->count(),
            'regions'    => $regions->count(),
            'last_sync'  => LegalAidProvider::max('updated_at'),
        ];

        return view('admin.providers.index', compact('providers', 'regions', 'counts'));
    }

    private function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'region'   => 'nullable|string|max:120',
            'district' => 'nullable|string|max:120',
            'location' => 'nullable|string|max:2000',
            'phone'    => 'nullable|string|max:60',
            'email'    => 'nullable|email|max:255',
            'language' => 'required|in:sw,en',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        if (!empty($data['district'])) $data['district'] = strtoupper($data['district']);

        $provider = LegalAidProvider::create($data);
        AdminActivityLog::record('provider.created', "Added legal aid provider \"{$provider->name}\"", $provider);

        return redirect()->route('admin.providers.index')->with('success', 'Provider added.');
    }

    public function edit(LegalAidProvider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, LegalAidProvider $provider)
    {
        $data = $request->validate($this->rules());
        if (!empty($data['district'])) $data['district'] = strtoupper($data['district']);

        $provider->update($data);
        AdminActivityLog::record('provider.updated', "Updated legal aid provider \"{$provider->name}\"", $provider);

        return redirect()->route('admin.providers.index')->with('success', 'Provider updated.');
    }

    public function destroy(LegalAidProvider $provider)
    {
        AdminActivityLog::record('provider.deleted', "Deleted legal aid provider \"{$provider->name}\"", null, ['id' => $provider->id]);
        $provider->delete();
        return back()->with('success', 'Provider deleted.');
    }

    public function sync(Request $request)
    {
        $pages = (int) ($request->pages ?: 16);
        $pages = max(1, min($pages, 50));

        try {
            $before = LegalAidProvider::count();
            Artisan::call('app:fetch-legal-aid-providers', ['--pages' => $pages]);
            $output = trim(Artisan::output());
            $after = LegalAidProvider::count();

            AdminActivityLog::record('provider.synced', "Synced providers from mslac.or.tz ({$pages} pages, " . ($after - $before) . " new)", null, ['pages' => $pages, 'before' => $before, 'after' => $after]);

            return back()->with('success', "Sync finished. Providers: {$before} → {$after}.")->with('tool_output', $output);
        } catch (\Throwable $e) {
            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = array_map(fn($h) => strtolower(trim($h)), fgetcsv($handle) ?: []);
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, array_pad($row, count($header), null));
            if (empty($data['name'])) continue;

            LegalAidProvider::updateOrCreate(
                ['name' => trim($data['name'])],
                [
                    'region'   => $data['region'] ?? null,
                    'district' => !empty($data['district']) ? strtoupper($data['district']) : null,
                    'location' => $data['location'] ?? null,
                    'phone'    => $data['phone'] ?? null,
                    'email'    => $data['email'] ?? null,
                    'language' => in_array($data['language'] ?? 'sw', ['sw', 'en']) ? $data['language'] : 'sw',
                ]
            );
            $count++;
        }
        fclose($handle);

        AdminActivityLog::record('provider.imported', "Imported {$count} providers from CSV", null, ['count' => $count]);

        return back()->with('success', "Imported or updated {$count} providers.");
    }

    public function export(): StreamedResponse
    {
        AdminActivityLog::record('provider.exported', 'Exported legal aid providers to CSV');

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['name', 'region', 'district', 'location', 'phone', 'email', 'language']);
            LegalAidProvider::orderBy('region')->orderBy('name')->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $p) {
                    fputcsv($out, [$p->name, $p->region, $p->district, $p->location, $p->phone, $p->email, $p->language]);
                }
            });
            fclose($out);
        }, 'hurulearn-legal-aid-providers-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
