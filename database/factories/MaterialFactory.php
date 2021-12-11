<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(2, 6));
        $title = $this->faker->realText(50);
        $post = "<h1>{$title}</h1>";
        foreach ($paragraphs as $para) {
            $post .= "<p>{$para}</p>";
        }
        return [
            'title' => $title,
            'content' => $post,
            'header' => $this->faker->imageUrl(800, 600, 'cats', true),
            'banner' => $this->faker->imageUrl(300, 160, 'people', true),
        ];
    }
}
