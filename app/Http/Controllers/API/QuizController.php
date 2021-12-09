<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizHistory;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a material quiz of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexQuiz($material)
    {
        $data = Quiz::where('material_id', $material)->first();
        if ($data)
            return $this->sendResponse('Berhasil !', $data);
        return $this->sendError('Quiz tidak ditemukan !', [], 404);
    }

    /**
     * Store a quiz answer from user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeQuiz(Request $request, $material)
    {
        $data = $this->validationInput($request->all(), [
            'quiz_id' => 'required|exists:quiz,id',
            'user_answer' => 'required',
            'score' => 'required|numeric'
        ]);

        if (is_object($data)) {
            return $data;
        }
        $data['user_id'] = auth()->user()->id;
        $duplicate = QuizHistory::where('user_id', $data['user_id'])->where('quiz_id', $data['quiz_id'])->first();
        if ($duplicate) {
            return $this->sendError('Quiz sudah dijawab sebelumnya !', [], 409);
        }
        QuizHistory::create($data);

        return $this->sendResponse('Berhasil !', $data);
    }

    /**
     * update a quiz answer from user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateQuiz(Request $request, $material)
    {
        $data = $this->validationInput($request->all(), [
            'quiz_id' => 'required|exists:quiz,id',
            'user_answer' => 'required',
            'score' => 'required|numeric'
        ]);

        if (is_object($data)) {
            return $data;
        }
        $data['user_id'] = auth()->user()->id;
        $answer = QuizHistory::where('user_id', $data['user_id'])->where('quiz_id', $data['quiz_id'])->first();
        if (!$answer) {
            return $this->sendError('Jawaban tidak ditemukan !', [], 404);
        }
        $answer->update($data);

        return $this->sendResponse('Berhasil !', $data);
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
}
