<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->paginate($request, Material::select('id', 'banner', 'title')->where('parent_id', $request->query('id') ?? 0)->where('active', true)->latest());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Material::find($id);
        try {
            $userAnswers = QuizAnswer::where('user_id', auth('sanctum')->user()->id)->where('quiz_id', $data->quiz->id)->get();
            if (count($userAnswers) > 0) {
                $data['score'] = $userAnswers->sum('score');
            }
            unset($data['quiz']);
        } catch (\Throwable $th) {
        }
        if ($data)
            return $this->sendResponse('Berhasil !', $data);
        return $this->sendError('Material tidak ditemukan !', [], 404);
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
