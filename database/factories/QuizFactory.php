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
        } else {
            $content = $this->faker->paragraph(rand(4, 6));
            $answer = $this->faker->paragraph(rand(4, 6));
        }
        return [
            'title' => $this->faker->realText(50),
            'content' => $content,
            'answer' => $answer,
            'type' => $type[$i]
        ];
    }
}
