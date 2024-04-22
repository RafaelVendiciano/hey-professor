<?php

    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\get;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;

    it('should be able to open a question to edit', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

            actingAs($user);

        
            $response = get(route('question.edit', $question));

        
            $response->assertSuccessful();
            
    });

    it('should return a view', function(){
        
            $user = User::factory()->create();
            $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

            actingAs($user);

        
            $response = get(route('question.edit', $question));

        
            $response->assertViewIs('question.edit');
            
    });

    it('should make sure that only questions with status draft can be edited', function(){
        
            $user = User::factory()->create();
            $draftQuestion = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);
            $notDraftQuestion = Question::factory()->for($user, 'createdBy')->create(['draft' => false]); 

            actingAs($user);

        
            $draftResponse = get(route('question.edit', $draftQuestion));
            $notDraftResponse = get(route('question.edit', $notDraftQuestion));

        
            $draftResponse->assertSuccessful();
            $notDraftResponse->assertForbidden();
            
    });

    it('should make sure that only the person who has created the question can edit the question', function(){
        
            $rightUser = User::factory()->create();
            $wrongUser = User::factory()->create();
            $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

        
            actingAs($wrongUser);
            get(route('question.edit', $question))->assertForbidden();

         
            actingAs($rightUser);
            get(route('question.edit', $question))->assertSuccessful();
    });

?>