<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrayUserID = User::all()->pluck('id');
        $send_id = fake()->randomElement($arrayUserID);
        $newArrayUserId =  $arrayUserID->filter(function ($item) use ($send_id) {
            return $item != $send_id;
        });
        $recieve_id = fake()->randomElement($newArrayUserId);
        $type = fake()->randomElement(['request','post','comment','like','share']);
        $content = "";
        switch ($type) {
            case 'request':
                $content = fake()->randomElement(['You have recieve friend request','Your friend request have been accept']);
                break;
            case 'post':
                $content = 'Some one just create a post';
                break;
            case 'comment':
                $content = 'Some one just comment on yout post';
                break;
            case 'like':
                $content = 'Some one just like your post';
                break;
            default:
            $content = 'Some one just share like your post';
                break;
        }
        return [
            'send_id'=>$send_id,
            'recieve_id'=> $recieve_id,
            'type'=>$type,
            'content'=> $content,
            'is_read'=> fake()->randomElement([1,0])
        ];
    }
}
