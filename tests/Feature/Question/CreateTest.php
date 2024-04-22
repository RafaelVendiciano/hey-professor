<?php

    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;

    it('should be able to create a new question bigger than 255 characters', function(){
        
            $user = User::factory()->create();
            actingAs($user);

        
            $request = post(route('question.store'), [
                'question' => str_repeat('*', 260) . '?'
            ]);

        
            $request->assertRedirect();
            assertDatabaseCount('questions', 1);
            assertDatabaseHas('questions', [
                'question' => str_repeat('*', 260) . '?'
            ]);
    });

    it('should create as draft all the time', function(){
        
            $user = User::factory()->create();
            actingAs($user);

        
            post(route('question.store'), [
                'question' => str_repeat('*', 260) . '?'
            ]);

        
            assertDatabaseHas('questions', [
                'question' => str_repeat('*', 260) . '?',
                'draft' => true
            ]);
    });

    it('should check if ends with question mark', function(){

            $user = User::factory()->create();
            actingAs($user);


            $request = post(route('question.store'), [
                'question' => str_repeat('*', 10)
            ]);


            $request->assertSessionHasErrors(['question' => 'are you sure that is a question? It is missing a question mark']);
            assertDatabaseCount('questions', 0);
    });

    it('should have at least 10 characters', function(){

            $user = User::factory()->create();
            actingAs($user);


            $request = post(route('question.store'), [
                'question' => str_repeat('*', 8) . '?'
            ]);


            $request->assertSessionHasErrors(['question' => __('validation.min.string', ['min' => 10, 
            'attribute' => 'question'])]);
            assertDatabaseCount('questions', 0);
    });

    test('only authenticated users can create a new question', function() {
        post(route('question.store'), [
            'question' => str_repeat('*', 8) . '?'
        ])->assertRedirect('login');
    });

    test('question should be unique', function() {
        $user = User::factory()->create();
        actingAs($user);

        Question::factory()->for($user, 'createdBy')->create(['question' => 'Alguma pergunta?']);

        post(route('question.store'), [
            'question' => 'Alguma pergunta?'
        ])->assertSessionHasErrors(['question' => 'A pergunta já existe!']);
    });

?>