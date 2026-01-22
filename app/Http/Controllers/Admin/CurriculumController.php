<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Curriculum;
use Illuminate\Support\Str;

class CurriculumController extends Controller
{
    public function index()
    {
        $curriculums = Curriculum::latest()->paginate(15);
        return view('admin.curriculum.index', compact('curriculums'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'language' => 'required|in:en,sw'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        $header = fgetcsv($handle);
        
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $title = $row[0];
            $content = $row[1];
            $summary = $row[2] ?? null;
            $tags = $row[3] ?? null;

            // Simple keyword generation if not provided
            $keywords = array_filter(explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $title))));
            if ($tags) {
                $keywords = array_unique(array_merge($keywords, array_map('trim', explode(',', Str::lower($tags)))));
            }

            Curriculum::create([
                'title' => $title,
                'content' => $content,
                'summary' => $summary,
                'tags' => $tags,
                'keywords' => $keywords,
                'language' => $request->language,
                'is_active' => true,
            ]);
            $count++;
        }
        fclose($handle);

        Cache::forget("curriculum_active_{$request->language}");

        return redirect()->back()->with('success', "Imported $count curriculum entries.");
    }

    public function destroy(Curriculum $curriculum)
    {
        Cache::forget("curriculum_active_{$curriculum->language}");
        $curriculum->delete();
        return redirect()->back()->with('success', 'Curriculum deleted.');
    }
}
