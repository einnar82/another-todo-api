<?php

namespace Tests\Feature\API\TasksController;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\Interfaces\ValidationTestInterface;

class UpdateTaskTest extends AbstractTaskResourceTest implements ValidationTestInterface
{
    use WithFaker;

    public function testUpdateSucceeds(): void
    {
        $title = $this->faker->word();
        $description = $this->faker->sentence();
        list($label, $task) = $this->createTestData();

        $response = $this->putJson(\sprintf('%s/%s', self::URI, $task->getKey()), [
            'title' => $title,
            'description' => $description,
            'label_ids' => [$label->getKey()],
        ]);

        $response->assertOk();
        $result = $response->json('data');
        $this->assertEquals($title, $result['title']);
        $this->assertEquals($description, $result['description']);
    }

    public function testValidationFailed(): void
    {
        list(, $task) = $this->createTestData();
        $response = $this->putJson(\sprintf('%s/%s', self::URI, $task->getKey()));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'title',
            'description',
            'label_ids',
        ]);
    }
}
