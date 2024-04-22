<?php

    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\get;
    use function Pest\Laravel\delete;
    use function Pest\Laravel\patch;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\assertDatabaseMissing;
    use function Pest\Laravel\assertSoftDeleted;
    use function Pest\Laravel\assertNotSoftDeleted;

    it('should be able to archive a question', function(){

        $user = User::factory()->create();
        $question = Question::factory()->create([
            'draft' => true,
            'created_by' => $user->id
        ]);
        actingAs($user);


        patch(route('question.archive', $question))
            ->assertRedirect();


        assertSoftDeleted('questions', ['id' => $question->id]);

        expect($question)->refresh()->deleted_at->not->toBeNull();

    });

    it('should make sure that only the person who has created the question can archive the question', function(){

            $rightUser = User::factory()->create();
            $wrongUser = User::factory()->create();
            $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
            actingAs($wrongUser);


            patch(route('question.archive', $question))->assertForbidden();


            actingAs($rightUser);
            patch(route('question.archive', $question))->assertRedirect();
    });

    it('should be able to restore an archived question', function(){

        $user = User::factory()->create();
        $question = Question::factory()->create([
            'draft' => true,
            'created_by' => $user->id,
            'deleted_at' => now()
        ]);
        actingAs($user);


        patch(route('question.restore', $question))
            ->assertRedirect();


        assertNotSoftDeleted('questions', ['id' => $question->id]);

        expect($question)->refresh()->deleted_at->toBeNull();
    });
 ?>