<?php

namespace App\Http\Controllers\Auth\Github;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    public function __invoke():RedirectResponse {
        
        return Socialite::driver('github')->redirect();
    }
}
