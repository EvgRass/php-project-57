<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private array $factoryData;
    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factoryData = Task::factory()->make()->toArray();
        $this->task = Task::factory()->create();
        $this->user = User::factory()->create();
    }

    public function testIndex(): void
    {
        // Создаем 2 задачи и передаем параметры фильтрации
        $tasks = Task::factory()->count(2)->create()->toArray();

        $response = $this->get(route('tasks.index'), [
            'status_id' => $tasks->first()->status_id,
            'created_by_id' => $tasks->first()->created_by_id,
            'assigned_to_id' => $tasks->first()->assigned_to_id,
        ]);

        $response->assertOk()
            ->assertViewIs('tasks.index')
            ->assertSee($tasks->first()->name)
            ->assertSee($tasks->last()->name);
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('tasks.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this->actingAs($this->user)
                         ->post(route('tasks.store'), $this->factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $this->factoryData);
    }

    public function testShow(): void
    {
        $response = $this->get(route('tasks.show', [$this->task]));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
                         ->get(route('tasks.edit', [$this->task]));

        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this->actingAs($this->user)
                         ->patch(route('tasks.update', $this->task), $this->factoryData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $this->factoryData);
    }

    public function testDestroy(): void
    {
        $response = $this->actingAs($this->user)
                         ->delete(route('tasks.destroy', [$this->task]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $this->task->id]);
    }
}
