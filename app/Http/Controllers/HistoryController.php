<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $attempts = QuizAttempt::with(['quiz.material'])
            ->where('user_id', Auth::id())
            ->whereNotNull('finished_at')
            ->latest('finished_at')
            ->paginate(15);

        return view('history.index', compact('attempts'));
    }
}
