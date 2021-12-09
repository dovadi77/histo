<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArrayConverter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message, $result)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => new ArrayConverter($result),
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Saving image to server
     *
     * @param array $input
     * @param string $image row name in DB for image URL
     * @param string $path path for images
     * @return void
     */
    public function save_image($input, $image, $path)
    {
        if (isset($input[$image])) {
            // file extension
            $ext = str_replace('image/', '', $input[$image]->getClientMimeType());

            // create name
            $random = md5($input['name'] . Str::random(10));
            $imgName = $random . "." . $ext;

            // upload file to storage
            /** @var \Illuminate\Support\Facades\Storage $disk */
            $disk = Storage::disk('public');
            $disk = $disk->putFileAs($path, $input[$image], $imgName);
            // replace from file to URL
            $input[$image] = env('APP_URL') . '/storage/' . $path . $imgName;
        } else if ($path == 'users/') {
            $color = ['f39c12', '6ab04c', 'ff6b6b', 'd06224', '2c272e', 'b983ff', '544179', 'e26a2c', '191a19', '6c4a4a'];
            $input[$image] = 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $input['name']) . '&background=' . $color[rand(0, 9)] . '&color=fff&size=128';
        }
        return $input;
    }

    /**
     * Validate input
     *
     * @param array $data
     * @param array $rules
     *
     * @return \Illuminate\Http\Response|array
     */
    public function validationInput($data, $rules)
    {
        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => implode(" | ", $validation->errors()->all()),
            ], 406);
        }
        return $validation->validated();
    }
}
