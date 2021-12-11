<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameAnswer;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function leaderboard(Request $request, $game)
    {
        $order = Game::find($game)->type == 'puzzle' ? 'user_time' : 'score';
        return $this->paginate($request, GameAnswer::where('game_id', $game)->orderBy($order, 'DESC')->with('user:id,name'));
    }

    public function index(Request $request)
    {
        return $this->paginate($request, Game::select('id', 'banner', 'title')->latest());
    }

    public function show($game)
    {
        $data = Game::where('id', $game)->with(['answers' => function ($q) {
            return $q->where('user_id', auth('sanctum')->user()->id)->first();
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
            } else if ($data['type'] == 'puzzle') {
                $arr = explode(';', $data['content']);
                $data['content'] = ['question' => $arr[0], 'puzzles' => array_slice($arr, 1, count($arr))];
            }
            $data['user_answer'] = $data['answers'][0] ?? null;
            unset($data['answers']);
            return $this->sendResponse('Berhasil !', $data);
        }
        return $this->sendError('Game tidak ditemukan !', [], 404);
    }

    public function store(Request $request, $game)
    {
        $data = $this->validationInput($request->all(), [
            'user_answer' => 'required',
            'user_time' => 'required|numeric|min:1'
        ]);

        if (is_object($data)) {
            return $data;
        }
        $data['user_id'] = auth()->user()->id;
        $data['game_id'] = $game;
        $duplicate = GameAnswer::where('user_id', $data['user_id'])->where('game_id', $game)->first();
        if ($duplicate) {
            return $this->sendError('Game sudah dimainkan sebelumnya !', [], 409);
        }

        // calculate score
        $data['score'] = $this->calculateScore($data, $game);

        $res = GameAnswer::create($data);

        return $this->sendResponse('Berhasil !', $res);
    }

    function calculateScore($data, $id)
    {
        $game = Game::find($id);
        if ($game['type'] == 'puzzle') {
            return 100;
        }
        $timePenalty = 0;
        switch ((int)(((($data['user_time'] / $game['max_time']) * 100) - 1) / 20)) {
            case 0:
                $timePenalty = 0;
                break;
            case 1:
                $timePenalty = 10;
                break;
            case 2:
                $timePenalty = 20;
                break;
            case 3:
                $timePenalty = 30;
                break;
            case 4:
                $timePenalty = 40;
                break;
            default:
                $timePenalty = 50;
                break;
        }
        if ($game['type'] == 'voice') {
            $answers = explode(' ', $game->answer);
            $userAnswers = explode(' ', $data['user_answer']);
            $correctWord = 0;
            foreach ($answers as $key => $val) {
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
            $res = ceil(($correctWord / count($answers)) * 100) - $timePenalty;
            return $res < 0 ? 0 : $res;
        }
        $userAnswers = explode(',', $data['user_answer']);
        $answers = explode(',', $game->answer);
        $correctAns = 0;
        foreach ($answers as $key => $val) {
            if ($val == $userAnswers[$key])
                $correctAns++;
        }
        $res = ceil(($correctAns / count($answers)) * 100) - $timePenalty;
        return $res < 0 ? 0 : $res;
    }
}
