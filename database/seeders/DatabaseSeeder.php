<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Achievement::factory(10)->create();
        $materials = \App\Models\Material::factory(10)->create();
        $materials->each(function ($m) {
            \App\Models\Quiz::factory()->create(['material_id' => $m->id]);
        });
        \App\Models\Game::factory(10)->create();
    }
}
