<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_created_with_a_future_reminder(): void
    {
        $user = User::factory()->create();
        $reminderAt = now()->addHour()->format('Y-m-d H:i');

        $response = $this
            ->actingAs($user)
            ->post('/tasks', [
                'title' => 'Review materi',
                'reminder_at' => $reminderAt,
            ]);

        $response->assertSessionHasNoErrors()->assertRedirect();
        $task = Task::where('user_id', $user->id)->first();

        $this->assertNotNull($task);
        $this->assertNotNull($task->reminder_at);
        $this->assertSame($reminderAt, $task->reminder_at->format('Y-m-d H:i'));
    }

    public function test_task_reminder_must_be_in_the_future(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/dashboard')
            ->post('/tasks', [
                'title' => 'Review materi',
                'reminder_at' => now()->subMinute()->format('Y-m-d H:i'),
            ]);

        $response->assertSessionHasErrors('reminder_at')->assertRedirect('/dashboard');
        $this->assertDatabaseCount('tasks', 0);
    }
}
