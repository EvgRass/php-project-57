<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\Label;
use App\Models\User;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private $factoryData;
    private Label $label;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factoryData = Label::factory()->make()->toArray();
        $this->label = Label::factory()->create();
        $this->user = User::factory()->create();
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
        $response = $this->actingAs($this->user)
                         ->post(route('labels.store'), $this->factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $this->factoryData);
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
                         ->get(route('labels.edit', [$this->label]));

        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this->actingAs($this->user)
                         ->patch(route('labels.update', $this->label), $this->factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $this->factoryData);
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
