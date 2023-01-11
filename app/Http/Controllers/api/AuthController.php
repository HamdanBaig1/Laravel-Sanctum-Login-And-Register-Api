<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Laravel\Sanctum\Sanctum;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'password' => 'required',
            'password-confirm' => 'required|same:password'
        ]);

        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response,400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $customer = User::Create($input);
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $success['token'] = $customer->createToken('MyApp')->plainTextToken;
        $success['name'] = $customer->namel;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'user registered succesful'
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response,400);
        }

        if(Auth::Attempt(['email'=>$request->email,'password'=>$request->password]))
        {
            $customer = Auth::user();

            $success['token'] = $customer->createToken('MyApp')->plainTextToken;
            $success['name'] = $customer->name;
    
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'user Login succesful'
            ];
    
            return response()->json($response, 200);
        }
        else
        {
            $response = [
                'success' => false,
                'message' => 'user Login ssssssssuccesful'
            ];
        }
    }
}
