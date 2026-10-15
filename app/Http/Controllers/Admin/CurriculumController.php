<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Curriculum;
use App\Models\AdminActivityLog;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $query = Curriculum::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%")
                  ->orWhere('tags', 'like', "%{$q}%");
            });
        }
        if (in_array($request->language, ['sw', 'en'])) {
            $query->where('language', $request->language);
        }
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $curriculums = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'      => Curriculum::count(),
            'sw'       => Curriculum::where('language', 'sw')->count(),
            'en'       => Curriculum::where('language', 'en')->count(),
            'inactive' => Curriculum::where('is_active', false)->count(),
        ];

        return view('admin.curriculum.index', compact('curriculums', 'counts'));
    }

    private function rules(): array
    {
        return [
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'summary'  => 'nullable|string|max:2000',
            'tags'     => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:2000',
            'language' => 'required|in:en,sw',
        ];
    }

    private function buildKeywords(string $title, ?string $tags, ?string $manual = null): array
    {
        $keywords = array_filter(explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $title))));
        if ($tags) {
            $keywords = array_merge($keywords, array_map('trim', explode(',', Str::lower($tags))));
        }
        if ($manual) {
            $keywords = array_merge($keywords, array_map('trim', explode(',', Str::lower($manual))));
        }
        return array_values(array_unique(array_filter($keywords, fn($k) => $k !== '')));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $item = Curriculum::create([
            'title'     => $data['title'],
            'content'   => $data['content'],
            'summary'   => $data['summary'] ?? Str::limit($data['content'], 150),
            'tags'      => $data['tags'] ?? null,
            'keywords'  => $this->buildKeywords($data['title'], $data['tags'] ?? null, $data['keywords'] ?? null),
            'language'  => $data['language'],
            'is_active' => true,
        ]);

        Cache::forget("curriculum_active_{$item->language}");
        AdminActivityLog::record('curriculum.created', "Added legal entry \"{$item->title}\"", $item);

        return redirect()->route('admin.curriculum.index')->with('success', 'Legal database entry created.');
    }

    public function edit(Curriculum $curriculum)
    {
        return view('admin.curriculum.edit', compact('curriculum'));
    }

    public function update(Request $request, Curriculum $curriculum)
    {
        $data = $request->validate($this->rules());

        $curriculum->update([
            'title'    => $data['title'],
            'content'  => $data['content'],
            'summary'  => $data['summary'] ?? null,
            'tags'     => $data['tags'] ?? null,
            'keywords' => $this->buildKeywords($data['title'], $data['tags'] ?? null, $data['keywords'] ?? null),
            'language' => $data['language'],
        ]);

        Cache::forget("curriculum_active_sw");
        Cache::forget("curriculum_active_en");
        AdminActivityLog::record('curriculum.updated', "Updated legal entry \"{$curriculum->title}\"", $curriculum);

        return redirect()->route('admin.curriculum.index')->with('success', 'Legal database entry updated.');
    }

    public function toggle(Curriculum $curriculum)
    {
        $curriculum->update(['is_active' => !$curriculum->is_active]);
        Cache::forget("curriculum_active_{$curriculum->language}");

        $state = $curriculum->is_active ? 'activated' : 'deactivated';
        AdminActivityLog::record("curriculum.{$state}", "Legal entry \"{$curriculum->title}\" {$state}", $curriculum);

        return back()->with('success', "Entry {$state}.");
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:curriculums,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $items = Curriculum::whereIn('id', $request->ids);
        $count = $items->count();

        match ($request->action) {
            'activate'   => $items->update(['is_active' => true]),
            'deactivate' => $items->update(['is_active' => false]),
            'delete'     => $items->delete(),
        };

        Cache::forget("curriculum_active_sw");
        Cache::forget("curriculum_active_en");
        AdminActivityLog::record("curriculum.bulk_{$request->action}", "Bulk {$request->action} on {$count} legal entries", null, ['ids' => $request->ids]);

        return back()->with('success', "{$count} entries: {$request->action} applied.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'doc_file' => 'required|file|mimes:csv,txt,json',
            'language' => 'required|in:en,sw'
        ]);

        $file = $request->file('doc_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $count = 0;

        if ($extension === 'csv') {
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 2) continue;

                Curriculum::create([
                    'title'     => $row[0],
                    'content'   => $row[1],
                    'summary'   => $row[2] ?? null,
                    'tags'      => $row[3] ?? null,
                    'keywords'  => $this->buildKeywords($row[0], $row[3] ?? null),
                    'language'  => $request->language,
                    'is_active' => true,
                ]);
                $count++;
            }
            fclose($handle);
        } elseif ($extension === 'json') {
            $data = json_decode(file_get_contents($file->getRealPath()), true);
            if (is_array($data)) {
                if (isset($data['title']) && isset($data['content'])) {
                    $data = [$data];
                }
                foreach ($data as $item) {
                    if (!isset($item['title']) || !isset($item['content'])) continue;

                    $tags = $item['tags'] ?? null;
                    if (is_array($tags)) $tags = implode(',', $tags);
                    $manual = isset($item['keywords']) && is_array($item['keywords']) ? implode(',', $item['keywords']) : null;

                    Curriculum::create([
                        'title'     => $item['title'],
                        'content'   => $item['content'],
                        'summary'   => $item['summary'] ?? null,
                        'tags'      => $tags,
                        'keywords'  => $this->buildKeywords($item['title'], $tags, $manual),
                        'language'  => $item['language'] ?? $request->language,
                        'is_active' => true,
                    ]);
                    $count++;
                }
            }
        } else {
            $txtContent = file_get_contents($file->getRealPath());
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $title = ucwords(str_replace(['-', '_'], ' ', $filename));

            $sections = array_filter(array_map('trim', preg_split('/\n\s*\n/', $txtContent)));

            foreach (array_values($sections) as $index => $section) {
                $sectionTitle = count($sections) > 1 ? "$title - Part " . ($index + 1) : $title;
                Curriculum::create([
                    'title'     => $sectionTitle,
                    'content'   => $section,
                    'summary'   => Str::limit($section, 150),
                    'tags'      => 'imported,txt',
                    'keywords'  => $this->buildKeywords($sectionTitle, null),
                    'language'  => $request->language,
                    'is_active' => true,
                ]);
                $count++;
            }
        }

        Cache::forget("curriculum_active_sw");
        Cache::forget("curriculum_active_en");
        AdminActivityLog::record('curriculum.imported', "Imported {$count} legal entries from {$file->getClientOriginalName()}", null, ['count' => $count, 'language' => $request->language]);

        return redirect()->back()->with('success', "Imported $count legal database entries.");
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Curriculum::query();
        if (in_array($request->language, ['sw', 'en'])) {
            $query->where('language', $request->language);
        }
        $items = $query->orderBy('id')->get();

        AdminActivityLog::record('curriculum.exported', "Exported {$items->count()} legal entries as JSON");

        $payload = $items->map(fn($c) => [
            'title'     => $c->title,
            'content'   => $c->content,
            'summary'   => $c->summary,
            'tags'      => $c->tags,
            'keywords'  => $c->keywords,
            'language'  => $c->language,
            'is_active' => $c->is_active,
        ])->values();

        return response()->streamDownload(
            fn() => print(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)),
            'hurulearn-legal-database-' . now()->format('Ymd-His') . '.json',
            ['Content-Type' => 'application/json']
        );
    }

    public function destroy(Curriculum $curriculum)
    {
        Cache::forget("curriculum_active_{$curriculum->language}");
        AdminActivityLog::record('curriculum.deleted', "Deleted legal entry \"{$curriculum->title}\"", null, ['id' => $curriculum->id]);
        $curriculum->delete();
        return redirect()->back()->with('success', 'Legal database entry deleted.');
    }
}
