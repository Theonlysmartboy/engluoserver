<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => 'required|regex:/^([0-12\s\-\+\(\)]*)$/|min:10|unique:users',
            'address' => ['string', 'max:255'],
            'postal_code' => ['string', 'max:100'],
            'avatar' => 'mimes:jpeg,png,jpg,gif',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if($request->icon){
            $temp_image = $request->icon;
            //check if the temp image is valid
            if($temp_image->isValid()){
                $extension = $temp_image->getClientOriginalExtension();
                $file_name = 'swiva_Category'.mt_rand(000, 9999999999).'.'.$extension;
                $large_path = public_path().'/uploads/large/' . $file_name;
                $thumnails_path = public_path().'/uploads/thumbnails/' . $file_name;
                //upload image to server
                Image::make($temp_image)->resize(600,600)->save($large_path);
                Image::make($temp_image)->resize(100,100)->save($thumnails_path);
            }
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->phone_number,
            'address' => $request->address,
            'code' => $request->code,
            'avatar' => $file_name,
            'password' => Hash::make($request->password),
            'api_token' => bin2hex(openssl_random_pseudo_bytes(30)),
        ]);
        $user->attachRole('user');


        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
