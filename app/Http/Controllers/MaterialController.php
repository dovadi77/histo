<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('material.index', ['materials' => Material::leftJoin('quiz as q', 'materials.id', '=', 'q.material_id')->orderBy('materials.parent_id')->orderBy('materials.updated_at', 'DESC')->get(['materials.id', 'materials.title', 'materials.created_at', 'materials.updated_at', 'materials.parent_id', 'q.type'])->toArray()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('material.add', ['parents' => Material::where('parent_id', 0)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'banner' => 'required|mimes:jpg,png,jpeg,svg',
                'header' => 'required|mimes:jpg,png,jpeg,svg',
                'content' => 'required',
                'isParent' => 'required',
                'parent' => 'numeric',
                'active' => 'required',
            ]);
            $input = $request->all();
            // save banner picture
            $input = $this->save_image($input, 'banner', 'material/');
            // save header picture
            $input = $this->save_image($input, 'header', 'material/');

            $input['parent_id'] = $input['parent'] ?? 0;

            $material = Material::create($input);

            if ($input['isParent'] == 0) {
                if (!$input['quiz_title'] || !$input['quiz']) {
                    return back()->with(['error' => 'Judul quiz / tipe quiz kosong!']);
                }
                if ($input['quiz'] == 'voice') {
                    $data = [
                        'title' => $input['quiz_title'],
                        'content' => $input['questions'][0] ? $input['questions'][0] : $input['questions'][1],
                        'answer' => $input['answers'][0] ? $input['answers'][0] : $input['answers'][1],
                        'material_id' => $material->id,
                        'type' => $input['quiz']
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
                        'title' => $input['quiz_title'],
                        'content' => substr($content, 0, -1),
                        'answer' => implode(",", $input['answers']),
                        'material_id' => $material->id,
                        'type' => $input['quiz']
                    ];
                }
                Quiz::create($data);
            }
            return back()->with(['success' => 'Berhasil menambah Material !']);
        } catch (\Throwable $th) {

            return back()->with(['error' => 'Terjadi kesalahan pada sistem !' . (env('APP_ENV') == 'production' ? '' : $th)]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        return view('material.edit', ['material' => $material, 'parents' => Material::where('parent_id', 0)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        try {
            $request->validate([
                'title' => 'required',
                'banner' => 'mimes:jpg,png,jpeg,svg',
                'header' => 'mimes:jpg,png,jpeg,svg',
                'content' => 'required',
                'parent' => 'numeric',
                'active' => 'required',
            ]);
            $input = $request->all();
            // save banner picture
            if (array_key_exists('banner', $input))
                $input = $this->save_image($input, 'banner', 'material/');
            // save header picture
            if (array_key_exists('header', $input))
                $input = $this->save_image($input, 'header', 'material/');

            $input['parent_id'] = $input['parent'] ?? 0;

            $material->update($input);

            if ($material->parent_id != 0) {
                if ($material->quiz->type == 'voice') {
                    $data = [
                        'title' => $input['quiz_title'],
                        'content' => $input['questions'][0] ? $input['questions'][0] : $input['questions'][1],
                        'answer' => $input['answers'][0] ? $input['answers'][0] : $input['answers'][1],
                        'material_id' => $material->id
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
                        'title' => $input['quiz_title'],
                        'content' => substr($content, 0, -1),
                        'answer' => implode(",", $input['answers']),
                        'material_id' => $material->id
                    ];
                }
                $material->quiz->update($data);
            }
            return back()->with(['success' => 'Berhasil mengubah Material !']);
        } catch (\Throwable $th) {
            return back()->with(['error' => 'Terjadi kesalahan pada sistem !' . (env('APP_ENV') == 'production' ? '' : $th)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        try {
            $material->delete();
            return back()->with(['success' => 'Berhasil menghapus Material !']);
        } catch (\Throwable $th) {
            return back()->with(['error' => 'Terjadi kesalahan pada sistem !' . (env('APP_ENV') == 'production' ? '' : $th)]);
        }
    }
}
