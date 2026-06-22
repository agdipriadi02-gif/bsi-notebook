<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        $materials = Auth::user()->materials()->latest()->paginate(12);
        return view('library.index', compact('materials'));
    }

    public function show(Material $material)
    {
        if ($material->user_id !== Auth::id()) {
            abort(403);
        }
        return redirect()->route('dashboard', ['material' => $material->id]);
    }
}
