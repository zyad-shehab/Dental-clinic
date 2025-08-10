<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DoctorModel;
use App\Models\PatientModel;

class AjaxController extends Controller
{
   public function searchPatients(Request $request)
{
    $term = $request->get('term', '');

    $patients = PatientModel::whereRaw("REPLACE(name, ' ', '') LIKE REPLACE(?, ' ', '')", ["%{$term}%"])
        ->select('id', 'name')
        ->limit(20)
        ->get();

    return response()->json($patients);
}



    public function searchDoctors(Request $request)
{
    $term = $request->get('term', '');

    $doctors = DoctorModel::whereRaw("REPLACE(name, ' ', '') LIKE REPLACE(?, ' ', '')", ["%{$term}%"])
        ->select('id', 'name')
        ->limit(20)
        ->get();

    return response()->json($doctors);
}
}
