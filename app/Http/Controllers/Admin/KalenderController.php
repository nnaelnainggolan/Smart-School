<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function index()
    {
        $kalender = KalenderAkademik::orderBy('tanggal_mulai', 'desc')->paginate(15);
        return view('admin.kalender.index', compact('kalender'));
    }

    public function create()
    {
        return view('admin.kalender.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kategori' => 'required|in:akademik,libur,ujian,acara',
        ]);
        KalenderAkademik::create($request->all());
        return redirect()->route('admin.kalender.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function destroy(KalenderAkademik $kalender)
    {
        $kalender->delete();
        return redirect()->route('admin.kalender.index')->with('success', 'Agenda berhasil dihapus.');
    }
}
