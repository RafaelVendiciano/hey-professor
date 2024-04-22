<?php

    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\get;
    use function Pest\Laravel\delete;
    use function Pest\Laravel\artisan;
    use function Pest\Laravel\patch;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\assertDatabaseMissing;
    use function Pest\Laravel\assertSoftDeleted;
    use function Pest\Laravel\assertNotSoftDeleted;

    it('should prune records deleted more than a month ago', function(){
        
        $question = Question::factory()->create([
            'deleted_at' => now()->subMonths(2)
        ]);

        assertSoftDeleted('questions', ['id' => $question->id]);

        artisan('model:prune');

        assertDatabaseMissing('questions', ['id' => $question->id]);

    });

    
 ?>