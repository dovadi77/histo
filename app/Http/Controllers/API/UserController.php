<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayConverter;
use App\Models\AchievementHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->user();
        $input['achievements'] = AchievementHistory::where('user_id', $input['id'])->with('achievement:id,name,image')->select('id', 'achievement_id', 'created_at')->get();
        return $this->sendResponse('Berhasil mengambil data', $input);
    }

    /**
     * Store a newly achievement user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAchievement(Request $request)
    {
        $data = $this->validationInput($request->all(), [
            'achievement_id' => 'required|exists:achievements,id'
        ]);

        if (is_object($data)) {
            return $data;
        }
        $data['user_id'] = auth()->user()->id;
        $duplicate = AchievementHistory::where('user_id', $data['user_id'])->where('achievement_id', $data['achievement_id'])->first();
        if ($duplicate) {
            return $this->sendError('Achievement sudah ada sebelumnya !', [], 409);
        }
        $save = AchievementHistory::create($data);

        return $this->sendResponse('Berhasil !', $save);
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
