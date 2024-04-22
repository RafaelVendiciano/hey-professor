<?php

    use App\Models\User;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\put;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use App\Models\Question;

    it('should be able to like a question', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->create();
            actingAs($user);

        
            post(route('question.like', $question))->assertRedirect();

        
            assertDatabaseHas('votes', [
                'question_id' => $question->id,
                'like' => 1,
                'dislike' => 0,
                'user_id' => $user->id
            ]);
    });

    it('should not be able to like a question more than one time', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->create();
            actingAs($user);

        
            post(route('question.like', $question));
            post(route('question.like', $question));
            post(route('question.like', $question));
            post(route('question.like', $question));

        
            expect($user->votes()->where('question_id', '=', $question->id)->get())
            ->toHaveCount(1);
    });


    it('should be able to dislike a question', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->create();
            actingAs($user);

        
            post(route('question.dislike', $question))->assertRedirect();

        
            assertDatabaseHas('votes', [
                'question_id' => $question->id,
                'like' => 0,
                'dislike' => 1,
                'user_id' => $user->id
            ]);
    });

    it('should not be able to dislike a question more than one time', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->create();
            actingAs($user);

        
            post(route('question.dislike', $question));
            post(route('question.dislike', $question));
            post(route('question.dislike', $question));
            post(route('question.dislike', $question));

        
            expect($user->votes()->where('question_id', '=', $question->id)->get())
            ->toHaveCount(1);
    });
?>