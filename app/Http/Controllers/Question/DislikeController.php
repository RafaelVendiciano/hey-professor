<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;

class DislikeController extends Controller
{
    public function __invoke(Question $question):RedirectResponse {
        
        user()->dislike($question);

        return back();
    }
}
