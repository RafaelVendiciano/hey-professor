<?php

    use App\Models\User;
    use App\Models\Question;
    use Illuminate\Pagination\LengthAwarePaginator;

    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\post;
    use function Pest\Laravel\get;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;

    it('should list all the questions', function(){
        
            $user = User::factory()->create();
            $questions = Question::factory()->count(5)->create();

            actingAs($user);

        
            $response = get(route('dashboard'));

        
        foreach($questions as $q) {
            $response->assertSee($q->question);
        }
            
    });

    it('should paginate the result', function(){
        
            $user = User::factory()->create();
            Question::factory()->count(20)->create();

            actingAs($user);

        
            $response = get(route('dashboard'));

        
            $response->assertViewHas('questions', fn($value) => $value instanceof LengthAwarePaginator);
            
    });

    /* 
        it('should order by like and dislike. most liked should be at the top and most disliked questions should be at the bottom', function(){
        
            $user = User::factory()->create();
            $secondUser = User::factory()->create();

            Question::factory()->count(20)->create();

            $mostLikedQuestion = Question::find(3);
            $mostDislikedQuestion = Question::find(1);

            $user->like($mostLikedQuestion);
            $secondUser->dislike($mostDislikedQuestion);

            actingAs($user);

        
            $response = get(route('dashboard'));

        
            $response->assertViewHas('questions', function ($questions) { 
                
                expect($questions)->first()->id->toBe(3);
                expect($questions)->last()->id->toBe(1);


                return true;
            });
            
        });
    */
?>