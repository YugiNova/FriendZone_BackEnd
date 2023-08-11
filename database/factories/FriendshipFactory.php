<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friendship>
 */
class FriendshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrayUserID = User::all()->pluck('id');
        $user_id = fake()->randomElement($arrayUserID);
        $newArrayUserId =  $arrayUserID->filter(function ($item) use ($user_id) {
            return $item != $user_id;
        });
        $friend_id = fake()->randomElement($newArrayUserId);
        return [
            'user_id'=>$user_id,
            'friend_id'=> $friend_id,
            'status'=>fake()->randomElement(['friend','request'])
        ];
    }
}
