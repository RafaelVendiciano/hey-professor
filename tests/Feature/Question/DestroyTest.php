<?php

    use App\Models\User;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\put;
    use function Pest\Laravel\delete;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\assertDatabaseMissing;

    use App\Models\Question;

    it('should be able to delete a question', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->create([
                'draft' => true,
                'created_by' => $user->id
            ]);
            actingAs($user);

        
            delete(route('question.destroy', $question))->assertRedirect();

        
            assertDatabaseMissing('questions', ['id' => $question->id]);
    });

    it('should make sure that only the person who has created the question can delete the question', function(){
        
            $rightUser = User::factory()->create();
            $wrongUser = User::factory()->create();
            $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
            actingAs($wrongUser);

        
            delete(route('question.destroy', $question))->assertForbidden();

         
            actingAs($rightUser);
            delete(route('question.destroy', $question))->assertRedirect();
    });

?>