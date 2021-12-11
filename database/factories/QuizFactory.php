<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = ['voice', 'multiple'];
        $i = rand(0, 1);
        if ($i == 1) {
            $paragraphs = $this->faker->paragraphs(rand(2, 6));
            $content = '';
            foreach ($paragraphs as $para) {
                $content .= "{$para};";
            }
            $content = substr($content, 0, -1);
            $answer = rand(0, count($paragraphs) - 2);
        } else {
            $content = $this->faker->paragraph(rand(4, 6));
            $answer = $this->faker->paragraph(rand(4, 6));
        }
        return [
            'material_id' => random_int(1, 10),
            'title' => $this->faker->realText(50),
            'content' => $content,
            'answer' => $answer,
            'type' => $type[$i]
        ];
    }
}
