<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_message()
    {
        // Создаем отправителя
        $sender = User::create([
            'name' => 'user123',
            'email' => 'user123@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        // Создаем получателя
        $receiver = User::create([
            'name' => 'receiver',
            'email' => 'receiver@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($sender, 'api')->postJson('/api/messages', [
            'receiver_id' => $receiver->id,
            'message' => 'Hello!',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Hello!']);
    }

    

    public function test_delete_message()
    {
        // Создаем отправителя
        $sender = User::create([
            'name' => 'user123',
            'email' => 'user123@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        // Создаем получателя
        $receiver = User::create([
            'name' => 'receiver',
            'email' => 'receiver@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        // Создаем сообщение вручную
        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => 'This is a test message',
            'is_read' => false,
        ]);

        $response = $this->actingAs($sender, 'api')->deleteJson("/api/messages/{$message->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => 'Message deleted successfully']);
    }
}