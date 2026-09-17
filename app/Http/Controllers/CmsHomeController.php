<?php

namespace App\Http\Controllers;

use App\Models\Akademik\Fakultas;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\HomepageSection;
use App\Models\Pengaturan\WebSetting;
use App\Models\Publikasi\Berita;
use App\Models\Publikasi\KalenderAkademik;
use App\Models\Publikasi\Pengumuman;
use Illuminate\Support\Facades\Schema;

class CmsHomeController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('web_settings')) {
            return view('welcome');
        }

        $webs = WebSetting::first();
        $sections = Schema::hasTable('homepage_sections')
            ? HomepageSection::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            : collect();

        return view('central.cms-home', [
            'webs' => $webs,
            'sections' => $sections,
            'beritas' => Berita::where('status', 'Publish')->orderByDesc('created_at')->take(4)->get(),
            'pengumuman' => Pengumuman::where('status', 'Publish')->orderByDesc('created_at')->take(4)->get(),
            'kalender' => KalenderAkademik::where('status', 'Publish')->orderBy('start_date')->take(6)->get(),
            'programStudis' => ProgramStudi::where('status', 'Aktif')->with('fakultas')->orderBy('name')->take(6)->get(),
            'fakultas' => Fakultas::withCount(['programStudis' => fn ($q) => $q->where('status', 'Aktif')])->take(3)->get(),
        ]);
    }
}
