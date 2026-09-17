<?php

namespace App\Http\Controllers\Master\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\HomepageSection;
use App\Models\Pengaturan\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomepageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $webs = WebSetting::first();
        $sections = HomepageSection::orderBy('sort_order')->orderBy('id')->get();

        return view('master.pengaturan.homepage-index', [
            'user' => $user,
            'webs' => $webs,
            'spref' => $user ? $user->prefix : '',
            'menus' => 'Pengaturan',
            'pages' => 'Front Page',
            'academy' => $webs->school_apps . ' by ' . $webs->school_name,
            'sections' => $sections,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section_key' => 'required|string|max:100|alpha_dash',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = Auth::id();

        HomepageSection::create($data);

        return back()->with('success', 'Section front page berhasil ditambahkan.');
    }

    public function update(Request $request, HomepageSection $section)
    {
        $data = $request->validate([
            'section_key' => 'required|string|max:100|alpha_dash|unique:homepage_sections,section_key,' . $section->id,
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = Auth::id();
        $section->update($data);

        return back()->with('success', 'Section front page berhasil diperbarui.');
    }

    public function destroy(HomepageSection $section)
    {
        $section->update(['deleted_by' => Auth::id()]);
        $section->delete();

        return back()->with('success', 'Section front page berhasil dihapus.');
    }
}
