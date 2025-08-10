<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class AuthenticatedController extends Controller{
// {
//     public function login(Request $request){
//     $credentials = $request->only('username', 'password');

//     if (Auth::attempt($credentials)) {
//         return redirect()->route('admin.index'); // أو أي مكان تريده
//     }

//     return back()->with('danger', 'اسم المستخدم أو كلمة المرور غير صحيحة');
// }
//     public function Authenticated(Request $request){
//         creds
//     }
     public function login()  {
        return view('Admin.login');
    }
    public function authenticate(Request $request){
       $valdat=$request->validate([
            'username' => 'required',
            'password' => 'required',    
        ]);

        $username=$request->input('username');
        $password=$request->input('password');
        if(Auth::attempt(['username' => $username, 'password' => $password])){
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }
        else{
            return redirect()->back()->with('error','خطا في اسم المستخدم او كلمة المرور');
        }
    }
    public function logout(Request $request){
     auth::logout();
     $request->session()->invalidate();
     $request->session()->regenerate();
     return redirect()->route('user.login.page');
    }

}
