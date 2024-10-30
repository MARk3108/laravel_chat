<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $sender) {
            foreach ($users as $receiver) {
                if ($sender->id !== $receiver->id) {
                    Message::create([
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'message' => 'Hello from ' . $sender->name . ' to ' . $receiver->name,
                        'is_read' => false,
                    ]);
                }
            }
        }
    }
}
