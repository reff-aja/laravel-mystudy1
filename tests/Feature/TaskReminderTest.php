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
        $localReminderAt = now()->addHour()->setTimezone('Asia/Jakarta')->startOfMinute();
        $reminderAt = $localReminderAt->format('Y-m-d\\TH:i');
        $expectedReminderAtUtc = $localReminderAt->copy()->utc()->format('Y-m-d H:i:s');

        $response = $this
            ->actingAs($user)
            ->post('/tasks', [
                'title' => 'Review materi',
                'reminder_at' => $reminderAt,
                'reminder_timezone' => 'Asia/Jakarta',
            ]);

        $response->assertSessionHasNoErrors()->assertRedirect();
        $task = Task::where('user_id', $user->id)->first();

        $this->assertNotNull($task);
        $this->assertNotNull($task->reminder_at);
        $this->assertSame($expectedReminderAtUtc, $task->reminder_at->format('Y-m-d H:i:s'));
    }

    public function test_dashboard_renders_due_reminders_and_notification_container(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Review materi',
            'reminder_at' => now()->subMinute(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertSee('id="reminder-toast-container"', false)
            ->assertSee('data-reminder-id="'.$task->id.'"', false)
            ->assertSee('Waktunya mengerjakan:', false);
    }

    public function test_task_reminder_must_be_in_the_future(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/dashboard')
            ->post('/tasks', [
                'title' => 'Review materi',
                'reminder_at' => now()->subMinutes(2)->setTimezone('Asia/Jakarta')->format('Y-m-d\\TH:i'),
                'reminder_timezone' => 'Asia/Jakarta',
            ]);

        $response->assertSessionHasErrors('reminder_at')->assertRedirect('/dashboard');
        $this->assertDatabaseCount('tasks', 0);
    }
}
