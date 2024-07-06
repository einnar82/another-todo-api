<?php

namespace Tests\Feature\API\TasksController;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\Interfaces\ValidationTestInterface;
use Tests\TestCase;

class CreateTaskTest extends TestCase implements ValidationTestInterface
{
    use WithFaker;

    public function testCreateSucceeds(): void
    {
        $title = $this->faker->word();
        $description = $this->faker->sentence();
        $label = $this->faker->word();

        $response = $this->postJson(self::URI, [
            'title' => $title,
            'description' => $description,
            'labels' => [$label],
        ]);

        $response->assertCreated();
        $result = $response->json('data');
        $this->assertEquals($title, $result['title']);
        $this->assertEquals($description, $result['description']);
    }

    public function testValidationFailed(): void
    {
        $response = $this->postJson(self::URI);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'title',
            'description',
            'labels',
        ]);
    }
}
