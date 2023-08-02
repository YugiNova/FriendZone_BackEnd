<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrayUserID = User::all()->pluck('id');
        $user_id = fake()->unique()->randomElement($arrayUserID);
        $now = Carbon::now();
        $past = Carbon::now()->copy()->subYear(29);
        return [
            'user_id'=>$user_id,
            'gender'=> fake()->randomElement(['male','female']),
            'dob'=>fake()->dateTimeBetween($past,$now),
            'introduce'=>fake()->text(),
            'cover_image_url'=>null
        ];
    }
}
