<?php
namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BantuanController extends Controller
{
    public function index() {
        $w=WebSetting::first();
        return view('private.mahasiswa.menu-page',[
            'webs'=>$w,'user'=>Auth::guard('mahasiswa')->user(),'menus'=>'Bantuan',
            'pages'=>'Bantuan','academy'=>$w?$w->school_apps.' by '.$w->school_name:'SIAKAD',
            'title'=>'Bantuan','message'=>'Silakan gunakan formulir bantuan untuk menyampaikan kendala penggunaan SIAKAD.'
        ]);
    }
    public function kirimPesan(Request $request) {
        $request->validate(['pesan'=>'required|string|max:2000']);
        return back()->with('success','Pesan bantuan berhasil dikirim.');
    }
}
