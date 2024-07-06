<?php

namespace Tests\Feature\API\TasksController;

use App\Models\Label;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Interfaces\ValidationTestInterface;
use Tests\TestCase;

class CreateTaskTest extends TestCase implements ValidationTestInterface
{
    use WithFaker;

    public function testCreateSucceeds(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create();
        $title = $this->faker->word();
        $description = $this->faker->sentence();

        $response = $this->postJson(self::URI, [
            'title' => $title,
            'description' => $description,
            'label_ids' => [$label->getKey()],
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
            'label_ids',
        ]);
    }
}
