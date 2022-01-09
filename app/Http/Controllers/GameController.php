<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('game.index', ['game' => Game::orderBy('updated_at', 'DESC')->get(['id', 'title', 'level', 'active', 'type', 'created_at', 'updated_at'])->toArray()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('game.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'banner' => 'required|mimes:jpg,png,jpeg,svg',
            'level' => 'required',
            'active' => 'required',
            'max_time' => 'required|numeric|min:10',
            'game' => 'required'
        ]);
        try {
            $input = $request->all();

            // save banner picture
            $input = $this->save_image($input, 'banner', 'game/');

            if ($input['game'] == 'voice') {
                $data = [
                    'content' => $input['questions'][0] ? $input['questions'][0] : $input['questions'][1],
                    'answer' => $input['answers'][0] ? $input['answers'][0] : $input['answers'][1],
                ];
            } else {
                $content = "";
                array_pop($input['questions']);
                array_pop($input['answers']);
                for ($i = 0; $i < count($input['questions']); $i++) {
                    $content .= $input['questions'][$i] . ";";
                    for ($j = $i * 4; $j < ($i + 1) * 4; $j++) {
                        $content .= $input['choices'][$j];
                        if ($j != ($i + 1) * 4 - 1) {
                            $content .= ';';
                        }
                    }
                    $content .= '|';
                }
                $data = [
                    'content' => substr($content, 0, -1),
                    'answer' => implode(",", $input['answers']),
                ];
            }
            Game::create(array_merge($data, [
                'title' => $input['title'],
                'level' => $input['level'],
                'active' => $input['active'],
                'type' => $input['game'],
                'banner' => $input['banner'],
                'max_time' => $input['max_time']
            ]));
            return back()->with(['success' => 'Berhasil menambah game !']);
        } catch (\Throwable $th) {
            // dd($th);
            return back()->with(['error' => 'Terjadi kesalahan pada sistem !' . (env('APP_ENV') == 'production' ? '' : $th)]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('game.edit', ['game' => $game]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'title' => 'required',
            'banner' => 'nullable|mimes:jpg,png,jpeg,svg',
            'level' => 'required',
            'active' => 'required',
            'max_time' => 'required|numeric|min:10',
        ]);
        try {
            $input = $request->all();

            // save banner picture
            if (array_key_exists('banner', $input))
                $input = $this->save_image($input, 'banner', 'game/');

            if ($game['type'] == 'voice') {
                $data = [
                    'content' => $input['questions'][0] ? $input['questions'][0] : $input['questions'][1],
                    'answer' => $input['answers'][0] ? $input['answers'][0] : $input['answers'][1],
                ];
            } else {
                $content = "";
                for ($i = 0; $i < count($input['questions']); $i++) {
                    $content .= $input['questions'][$i] . ";";
                    for ($j = $i * 4; $j < ($i + 1) * 4; $j++) {
                        $content .= $input['choices'][$j];
                        if ($j != ($i + 1) * 4 - 1) {
                            $content .= ';';
                        }
                    }
                    $content .= '|';
                }
                $data = [
                    'content' => substr($content, 0, -1),
                    'answer' => implode(",", $input['answers']),
                ];
            }
            $game->update(array_merge($data, [
                'title' => $input['title'],
                'level' => $input['level'],
                'active' => $input['active'] == '1',
                'max_time' => $input['max_time']
            ]));
            return back()->with(['success' => 'Berhasil mengubah game !']);
        } catch (\Throwable $th) {
            // dd($th);
            return back()->with(['error' => 'Terjadi kesalahan pada sistem !' . (env('APP_ENV') == 'production' ? '' : $th)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        try {
            $game->delete();
            return back()->with(['success' => 'Berhasil menghapus Game !']);
        } catch (\Throwable $th) {
            return back()->with(['error' => 'Terjadi kesalahan pada sistem !' . (env('APP_ENV') == 'production' ? '' : $th)]);
        }
    }
}
