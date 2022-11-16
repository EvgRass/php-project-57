<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\TaskStatus;
use App\Models\User;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private $factoryData;
    private $status;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factoryData = TaskStatus::factory()->make()->toArray();
        $this->status = TaskStatus::factory()->create();
        $this->user = User::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this->actingAs($this->user)
                         ->post(route('task_statuses.store'), $this->factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', $this->factoryData);
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
                         ->get(route('task_statuses.edit', [$this->status]));

        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this->actingAs($this->user)
                         ->patch(route('task_statuses.update', $this->status), $this->factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', $this->factoryData);
    }

    public function testDestroy(): void
    {
        $response = $this->actingAs($this->user)
                         ->delete(route('task_statuses.destroy', [$this->status]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('task_statuses', ['id' => $this->status->id]);
    }
}
