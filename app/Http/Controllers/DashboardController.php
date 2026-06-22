<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $materials = $user->materials()->latest()->get();

        $activeMaterialId = $request->query('material');
        $activeMaterial = null;

        if ($activeMaterialId) {
            $activeMaterial = $user->materials()->find($activeMaterialId);
        }

        if (!$activeMaterial && $materials->isNotEmpty()) {
            $activeMaterial = $materials->first();
        }

        $chatMessages = [];
        if ($activeMaterial) {
            $chatMessages = ChatMessage::where('user_id', $user->id)
                ->where('material_id', $activeMaterial->id)
                ->orderBy('created_at')
                ->get();
        }

        return view('dashboard.index', compact('materials', 'activeMaterial', 'chatMessages'));
    }
}
