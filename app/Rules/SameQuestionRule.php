<?php

namespace App\Rules;

use Closure;
use App\Models\Question;
use Illuminate\Contracts\Validation\ValidationRule;

class SameQuestionRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->validationRule($value)) {
            $fail('A pergunta já existe!');
        }
    }

    private function validationRule(string $value): bool {

        return Question::where('question', $value)->exists();
    }
}
