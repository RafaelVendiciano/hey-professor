<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View {
        
        return view('dashboard', [
            'questions' => Question::query()
                                   ->when(request()->has('search'), function(Builder $query) {
                                        $query->where('question', 'like', '%' . request()->search . '%');
                                   })
                                   ->withSum('votes', 'like')
                                   ->withSum('votes', 'dislike')
                                   ->orderByRaw('case when votes_sum_like is null then 0 else votes_sum_like end desc, case when votes_sum_dislike is null then 0 else votes_sum_dislike end')
                                   ->paginate(10)
                                   
        ]);
    }
}
