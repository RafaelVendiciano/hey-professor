<?php

    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\get;
    use function Pest\Laravel\put;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;

it('should update the question in the database', function(){
        
        $user = User::factory()->create();
        $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

        actingAs($user);

    
        put(route('question.update', $question), [
            'question' => 'Updated Question?'
        ])->assertRedirect(route('question.index'));

        $question->refresh();

    
        expect($question)->question->toBe('Updated Question?');
    });

    it('should make sure that only questions with status draft can be updated', function(){
        
            $user = User::factory()->create();
            $draftQuestion = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);
            $notDraftQuestion = Question::factory()->for($user, 'createdBy')->create(['draft' => false]); 

            actingAs($user);

        
            $draftResponse = put(route('question.update', $draftQuestion), [
                'question' => 'Updated Question?'
            ]);
            $notDraftResponse = put(route('question.update', $notDraftQuestion), [
                'question' => 'Updated Question?'
            ]);

        
            $draftResponse->assertRedirect(route('question.index'));
            $notDraftResponse->assertForbidden();
            
    });

    it('should make sure that only the person who has created the question can update the question', function(){
        
            $rightUser = User::factory()->create();
            $wrongUser = User::factory()->create();
            $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

        
            actingAs($wrongUser);
            put(route('question.update', $question), [
                'question' => 'Updated Question?'
            ])->assertForbidden();

         
            actingAs($rightUser);
            put(route('question.update', $question), [
                'question' => 'Updated Question?'
            ])->assertRedirect(route('question.index'));
    });

    it('should be able to update a question bigger than 255 characters', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);
            actingAs($user);

        
            $request = put(route('question.update', $question), [
                'question' => str_repeat('*', 260) . '?'
            ]);

        
            $request->assertRedirect(route('question.index'));
            assertDatabaseCount('questions', 1);
            assertDatabaseHas('questions', [
                'question' => str_repeat('*', 260) . '?'
            ]);
    });

    it('should check if ends with question mark', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);
            actingAs($user);

        
            $request = put(route('question.update', $question), [
                'question' => str_repeat('*', 10)
            ]);

        
            $request->assertSessionHasErrors(['question' => 'are you sure that is a question? It is missing a question mark']);
            assertDatabaseHas('questions', [
                'question' => $question->question
            ]);
    });

    it('should have at least 10 characters', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);
            actingAs($user);

        
            $request = put(route('question.update', $question), [
                'question' => str_repeat('*', 8) . '?'
            ]);

        
            $request->assertSessionHasErrors(['question' => __('validation.min.string', ['min' => 10, 
            'attribute' => 'question'])]);
            assertDatabaseHas('questions', [
                'question' => $question->question
            ]);
    });

?>