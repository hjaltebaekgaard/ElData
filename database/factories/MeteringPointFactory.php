<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeteringPoint>
 */
class MeteringPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $base_id = '900000000';
        $types = ['D07', 'D06', 'D14', 'D15'];

        return [
            'id' =>  $base_id . strval(fake()->unique()->randomNumber(9, true)),
            'type' => $types[rand(0, 3)],
            'sheet_id' => Str::random(44),
            'user_id' => User::factory()
        ];
    }
}

/* $table->string('meteringPointId')->unique();
            $table->bigInteger('userId', false, true);
            $table->string('type');
            $table->string('sheetId');
            $table->boolean('collectEnabled');
            $table->boolean('transferEnabled');
            $table->dateTimeTz('latestCollect');
            $table->dateTimeTz('latestTransfer'); */
