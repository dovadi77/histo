<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = ['voice', 'multiple', 'puzzle'];
        $level = ['easy', 'medium', 'hard'];
        $i = rand(0, 2);
        if ($i == 1) {
            $content = '';
            $answer = '';
            for ($x = 0; $x < rand(3, 5); $x++) {
                $paragraphs = $this->faker->paragraphs(5);
                foreach ($paragraphs as $para) {
                    $content .= "{$para};";
                }
                $content = substr($content, 0, -1) . "|";
                $answer .= (string)rand(0, count($paragraphs) - 2) . ",";
            }
            $content = substr($content, 0, -1);
            $answer = substr($answer, 0, -1);
        } else if ($i == 2) {
            $content = $this->faker->paragraph(rand(2, 4)) . ';';
            for ($x = 0; $x < 8; $x++) {
                $content .= "{$this->faker->imageUrl(100, 100)};";
            }
            $content = substr($content, 0, -1);
            $answer = 'puzzle';
        } else {
            $content = $this->faker->paragraph(rand(4, 6));
            $answer = $this->faker->paragraph(rand(4, 6));
        }
        return [
            'banner' => $this->faker->imageUrl(300, 160, 'people', true),
            'title' => $this->faker->realText(50),
            'content' => $content,
            'answer' => $answer,
            'type' => $type[$i],
            'level' => $level[rand(0, 2)],
            'max_time' => rand(8, 20)
        ];
    }
}
