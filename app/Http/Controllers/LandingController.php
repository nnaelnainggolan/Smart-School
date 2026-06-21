<?php
namespace App\Http\Controllers;

use App\Models\Berita;

class LandingController extends Controller
{
    public function index()
    {
        $berita = Berita::where('is_published', true)
            ->latest('published_at')->take(3)->get();
        return view('landing.index', compact('berita'));
    }
}
