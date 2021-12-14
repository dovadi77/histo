<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayConverter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle user login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            /** @var \App\Models\MyUserModel $user */
            $user = Auth::user();
            $data = [
                'token' => $user->createToken($user->username)->plainTextToken,
                'user' => new ArrayConverter($user)
            ];
            return $this->sendResponse('Login sukses !', $data);
        }
        return $this->sendError('Kredensial tidak cocok !');
    }

    /**
     * Register user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $input = $this->validationInput($request->all(), [
            'name' => 'nullable',
            'username' => 'required|alpha_num|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'mobile' => ['numeric', 'nullable', 'regex:/(^628|^08)/m'],
            'image' => 'nullable|mimes:jpg,png,jpeg,svg|max:2048|dimensions:min_width=100,min_height=100',
            'confirm_password' => 'required|same:password',
        ]);

        if (is_object($input)) {
            return $input;
        }
        $input['password'] = Hash::make($input['password']);
        // save profile picture
        $input = $this->save_image($input, 'image', 'users/');
        // save input
        $user = User::create($input);
        $data = [
            'token' => $user->createToken($user->username)->plainTextToken,
            'user' => new ArrayConverter($user)
        ];
        return $this->sendResponse('User berhasil ter-registrasi', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
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
