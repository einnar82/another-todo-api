<?php

namespace Tests\Feature\API\TasksController;

use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Interfaces\ValidationTestInterface;

class UpdateTaskTest extends AbstractTaskResourceTest implements ValidationTestInterface
{
    use WithFaker;

    public function testUpdateSucceeds(): void
    {
        $title = $this->faker->word();
        $description = $this->faker->sentence();
        $task = Task::factory()->create();
        $label = $this->faker->word();

        $response = $this->putJson(\sprintf('%s/%s', self::URI, $task->getKey()), [
            'title' => $title,
            'description' => $description,
            'labels' => [$label],
        ]);

        $response->assertOk();
        $result = $response->json('data');
        $this->assertEquals($title, $result['title']);
        $this->assertEquals($description, $result['description']);
    }

    public function testValidationFailed(): void
    {
        $task = Task::factory()->create();
        $response = $this->putJson(\sprintf('%s/%s', self::URI, $task->getKey()));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'title',
            'description',
            'labels',
        ]);
    }
}
