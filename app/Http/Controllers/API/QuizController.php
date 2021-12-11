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
    public function indexMaterial($material)
    {
        $data = Quiz::where('material_id', $material)->with(['answers' => function ($q) {
            return $q->where('user_id', auth()->user()->id)->first();
        }])
            ->first();
        if ($data) {
            if ($data['type'] == 'multiple') {
                $arr = explode(';', $data['content']);
                $data['content'] = ['question' => $arr[0], 'choices' => array_slice($arr, 1, count($arr))];
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
    public function storeMaterial(Request $request)
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
    public function updateMaterial(Request $request)
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

        $res = $answer->update($data);

        return $this->sendResponse('Berhasil !', $res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function calculateScore($data)
    {
        $quiz = Quiz::find($data['quiz_id']);
        if ($quiz['type'] == 'voice') {
            $quizAnswers = explode(' ', $quiz->answer);
            $userAnswers = explode(' ', $data['user_answer']);
            $correctWord = 0;
            foreach ($quizAnswers as $key => $val) {
                try {
                    $userAnswer = $userAnswers[$key];
                    $val = preg_replace('/[[:punct:]]/', '', $val);
                    $ans = preg_replace('/[[:punct:]]/', '',  $userAnswer);
                    if ($val == $ans)
                        $correctWord++;
                } catch (\Throwable $th) {
                    continue;
                }
            }
            return ceil(($correctWord / count($quizAnswers)) * 100);
        }
        return $data['user_answer'] == $quiz['answer'] ? 100 : 0;
    }
}
