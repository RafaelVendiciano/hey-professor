<?php

    use App\Models\User;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\put;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use App\Models\Question;

    it('should be able to publish a question', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->create([
                'draft' => true,
                'created_by' => $user->id
            ]);
            actingAs($user);

        
            put(route('question.publish', $question))->assertRedirect();

            $question->refresh();
        
            expect($question)->draft->toBeFalse();
    });

    it('should make sure that only the person who has created the question can publish the question', function(){
        
            $rightUser = User::factory()->create();
            $wrongUser = User::factory()->create();
            $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
            actingAs($wrongUser);

        
            put(route('question.publish', $question))->assertForbidden();

         
            actingAs($rightUser);
            put(route('question.publish', $question))->assertRedirect();
    });

?>