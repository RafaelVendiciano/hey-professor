<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Rules\SameQuestionRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Closure;
use Illuminate\Contracts\View\View;

class QuestionController extends Controller
{
    public function index(): View {

        
        return view('question.index', [
            'questions' => user()->questions()->get(),
            'archivedQuestions' => user()->questions()->onlyTrashed()->get()
        ]);
    }

    public function store():RedirectResponse {

        request()->validate([
            'question' => ['required', 
                            'min:10',
                            function (string $attribute, mixed $value, Closure $fail) {
                                if ($value[strlen($value) - 1] != '?') {
                                    $fail('are you sure that is a question? It is missing a question mark');
                                }
                            },
                            new SameQuestionRule()
                            ]
        ]);
        
        user()->questions()->create([
                    'question' => request()->question,
                    'draft' => true
                    ]);

        return back();
    }

    public function edit(Question $question): View {

        $this->authorize('update', $question);

        return view('question.edit', [
                    'question' => $question
        ]);
    }

    public function update(Question $question): RedirectResponse {

        $this->authorize('update', $question);

        request()->validate([
            'question' => ['required', 
                            'min:10',
                            function (string $attribute, mixed $value, Closure $fail) {
                                if ($value[strlen($value) - 1] != '?') {
                                    $fail('are you sure that is a question? It is missing a question mark');
                                }
                            }, ]
        ]);

        $question->question = request()->question;
        $question->save();

        return to_route('question.index');
    }

    public function archive(Question $question):RedirectResponse {

        $this->authorize('archive', $question);

        $question->delete();

        return back();
    }

    public function restore(int $id):RedirectResponse {

        // $this->authorize('destroy', $question);
        $question = Question::withTrashed()->find($id);

        $question->restore();

        return back();
    }

    public function destroy(Question $question):RedirectResponse {

        $this->authorize('destroy', $question);

        $question->forceDelete();

        return back();
    }
}
