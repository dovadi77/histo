<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a material quiz of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($material)
    {
        $data = Quiz::where('material_id', $material)->with(['answers' => function ($q) {
            return $q->where('user_id', auth()->user()->id)->first();
        }])
            ->first();
        if ($data) {
            if ($data['type'] == 'multiple') {
                $questions = explode('|', $data['content']);
                foreach ($questions as $i => $q) {
                    $arr = explode(';', $q);
                    $questions[$i] = ['question' => $arr[0], 'choices' => array_slice($arr, 1, count($arr))];
                }
                $data['content'] = $questions;
            } else {
                $data['content'] = explode('|', $data['content']);
            }
            $data['user_answer'] = $data['answers'][0] ?? null;
            unset($data['answers']);
            return $this->sendResponse('Berhasil !', $data);
        }
        return $this->sendError('Quiz tidak ditemukan !', [], 404);
    }

    /**
     * Store a quiz answer from user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validationInput($request->all(), [
            'quiz_id' => 'required|exists:quiz,id',
            'user_answer' => 'required'
        ]);

        if (is_object($data)) {
            return $data;
        }
        $data['user_id'] = auth()->user()->id;
        $duplicate = QuizAnswer::where('user_id', $data['user_id'])->where('quiz_id', $data['quiz_id'])->first();
        if ($duplicate) {
            return $this->sendError('Quiz sudah dijawab sebelumnya !', [], 409);
        }

        // calculate score
        $data['score'] = $this->calculateScore($data);

        $res = QuizAnswer::create($data);

        return $this->sendResponse('Berhasil !', $res);
    }

    /**
     * update a quiz answer from user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validationInput($request->all(), [
            'quiz_id' => 'required|exists:quiz,id',
            'user_answer' => 'required',
        ]);

        if (is_object($data)) {
            return $data;
        }
        $data['user_id'] = auth()->user()->id;
        $answer = QuizAnswer::where('user_id', $data['user_id'])->where('quiz_id', $data['quiz_id'])->first();
        if (!$answer) {
            return $this->sendError('Jawaban tidak ditemukan !', [], 404);
        }

        // calculate score
        $data['score'] = $this->calculateScore($data);

        $answer->update($data);

        return $this->sendResponse('Berhasil !', $answer);
    }

    function calculateScore($data)
    {
        $quiz = Quiz::find($data['quiz_id']);
        if ($quiz['type'] == 'voice') {
            $answers = explode(' ', $quiz->answer);
            $userAnswers = explode(' ', $data['user_answer']);
            $correctWord = 0;
            foreach ($answers as $key => $val) {
                try {
                    $userAnswer = strtolower($userAnswers[$key]);
                    $val = strtolower($val);
                    $val = preg_replace('/[[:punct:]]/', '', $val);
                    $ans = preg_replace('/[[:punct:]]/', '',  $userAnswer);
                    if ($val == $ans)
                        $correctWord++;
                } catch (\Throwable $th) {
                    continue;
                }
            }
            return ceil(($correctWord / count($answers)) * 100);
        }
        $userAnswers = explode(',', $data['user_answer']);
        $answers = explode(',', $quiz->answer);
        $correctAns = 0;
        foreach ($answers as $key => $val) {
            if ($val == $userAnswers[$key])
                $correctAns++;
        }
        return ceil(($correctAns / count($answers)) * 100);
    }
}
