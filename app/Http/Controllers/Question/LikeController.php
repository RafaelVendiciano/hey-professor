<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Vote;
use App\Models\User;

class LikeController extends Controller
{
    public function __invoke(Question $question):RedirectResponse {
        
        user()->like($question);

        return back();
    }
}
