<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Label;
use App\Models\User;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Label $label;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->label = Label::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('labels.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $factoryData = Label::factory()->make()->toArray();

        $response = $this->actingAs($this->user)
                         ->post(route('labels.store'), $factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $factoryData);
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
                         ->get(route('labels.edit', [$this->label]));

        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $factoryData = Label::factory()->make()->toArray();

        $response = $this->actingAs($this->user)
                         ->patch(route('labels.update', $this->label), $factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $factoryData);
    }

    public function testDestroy(): void
    {
        $response = $this->actingAs($this->user)
                         ->delete(route('labels.destroy', [$this->label]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', ['id' => $this->label->id]);
    }
}
