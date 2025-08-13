<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;

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
            return redirect()->route('dashboard')->with('success','تم تسجيل الدخول بنجاح');
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
    

    public function changePassword(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed', // تأكيد الباسورد الجديد
        ]);

        // جلب المستخدم الحالي كموديل Eloquent
        $user = User::find(Auth::id());

        // التحقق من أن كلمة المرور القديمة صحيحة
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // تحديث كلمة المرور
        $user->password = Hash::make($request->password);
        $user->save(); // حفظ التغييرات

        // تسجيل خروج كل الجلسات الأخرى (لزيادة الأمان)
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}



