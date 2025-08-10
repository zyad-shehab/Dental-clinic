<?php

namespace App\Http\Controllers;

use App\Models\Clinic_SessionsModel;
use App\Models\PatientModel;
use App\Models\ServicesModel;
use App\Models\TeethModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $patientCount=PatientModel::count();
        $sessionCount=Clinic_SessionsModel::count();
        $servicesCount=ServicesModel::count();
        return view('Admin.dashbord',compact('patientCount', 'sessionCount', 'servicesCount'));
    }

    public function login()
    {
        return view('Admin.login');
    }

    public function logout()
    {
        // Logic for logging out the admin
        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
