<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\Clinic_SessionsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\expensesController;
use App\Http\Controllers\financial_ReportController;
use App\Http\Controllers\lab_PaymentController;
use App\Http\Controllers\laboratory_purchasesController;
use App\Http\Controllers\laboratoryController;
use App\Http\Controllers\LaboratoryRequestsController;
use App\Http\Controllers\patient_PaymentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\storehouseController;
use App\Http\Controllers\TeethController;
use App\Http\Controllers\warehouse_PaymentController;
use App\Http\Controllers\warehousePurchasesController;
use App\Http\Controllers\WarehouseStockController;
use App\Models\Clinic_SessionsModel;
use App\Models\Laboratory_purchasesModel;
use App\Models\patient_PaymentModel;
use App\Models\PatientModel;
use App\Models\SecretaryModel;
use App\Models\ServicesModel;
use App\Models\storehouseModel;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.login');
})->name('loginPage');

Route::post('/admin/login', [AuthenticatedController::class, 'authenticate'])->name('authenticate');
Route::post('/admin/logout', [AuthenticatedController::class, 'logout'])->name('logout');

Route::get('/dashboard',[AdminController::class,'index'])->middleware('CheckRole:admin,secretary')->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['CheckRole:admin'])->group(function () {
    Route::get('admin/doctor',[DoctorController::class,'index'])->middleware('authUserAdmin')->name('admin.doctor.index');
    Route::get('admin/doctor/create',[DoctorController::class,'create'])->name('admin.doctor.create');
    Route::post('admin/doctor/store',[DoctorController::class,'store'])->name('admin.doctor.store');
    Route::get('admin/doctor/edit/{id}',[DoctorController::class,'edit'])->name('admin.doctor.edit');
    Route::put('admin/doctor/{id}',[DoctorController::class,'update'])->name('admin.doctor.update');
    Route::get('admin/doctor/show/{id}',[DoctorController::class,'show'])->name('admin.doctor.show');
    Route::delete('admin/doctor/destroy/{id}',[DoctorController::class,'destroy'])->name('admin.doctor.destroy');
    /////////////////////////////////////
    Route::get('admin/secretary',[SecretaryController::class,'index'])->name('admin.secretary.index');
    Route::get('admin/secretary/create',[SecretaryController::class,'create'])->name('admin.secretary.create');
    Route::post('admin/secretary/store',[SecretaryController::class,'store'])->name('admin.secretary.store');
    Route::get('admin/secretary/edit/{id}',[SecretaryController::class,'edit'])->name('admin.secretary.edit');
    Route::put('admin/secretary/{id}',[SecretaryController::class,'update'])->name('admin.secretary.update');
    Route::get('admin/secretary/show/{id}',[SecretaryController::class,'show'])->name('admin.secretary.show');
    Route::delete('admin/secretary/destroy/{id}',[SecretaryController::class,'destroy'])->name('admin.secretary.destroy');
    //////////////////////////////
    Route::get('admin/expenses',[expensesController::class,'index'])->name('expenses.index');
    Route::get('admin/expenses/create',[expensesController::class,'create'])->name('expenses.create');
    Route::post('admin/expenses/store',[expensesController::class,'store'])->name('expenses.store');
    Route::get('admin/expenses/edit/{id}',[expensesController::class,'edit'])->name('expenses.edit');
    Route::get('admin/expenses/{id}', [expensesController::class, 'show'])->name('expenses.show');
    Route::put('admin/expenses/update/{id}',[expensesController::class,'update'])->name('expenses.update');
    Route::delete('admin/expenses/destroy/{id}',[expensesController::class,'destroy'])->name('expenses.destroy');
    ///////////////////////////
    Route::get('admin/Report',[financial_ReportController::class,'getReport'])->name('Report.getReport');
    ////////////////////////
    Route::get('admin/laboratoryPurchases',[laboratory_purchasesController::class,'index'])->name('laboratoryPurchases.index');
    Route::get('admin/laboratoryPurchases/create',[laboratory_purchasesController::class,'create'])->name('laboratoryPurchases.create');
    Route::post('admin/laboratoryPurchases/store',[laboratory_purchasesController::class,'store'])->name('laboratoryPurchases.store');
    Route::get('admin/laboratoryPurchases/edit/{id}',[laboratory_purchasesController::class,'edit'])->name('laboratoryPurchases.edit');
    Route::get('admin/laboratoryPurchases/{id}', [laboratory_purchasesController::class, 'show'])->name('laboratoryPurchases.show');
    Route::put('admin/laboratoryPurchases/update/{id}',[laboratory_purchasesController::class,'update'])->name('laboratoryPurchases.update');
    Route::delete('admin/laboratoryPurchases/destroy/{id}',[laboratory_purchasesController::class,'destroy'])->name('laboratoryPurchases.destroy');
    ///////////////////////////
    Route::get('admin/warehousePurchases',[warehousePurchasesController::class,'index'])->name('warehousePurchases.index');
    Route::get('admin/warehousePurchases/create',[warehousePurchasesController::class,'create'])->name('warehousePurchases.create');
    Route::post('admin/warehousePurchases/store',[warehousePurchasesController::class,'store'])->name('warehousePurchases.store');
    Route::get('admin/warehousePurchases/edit/{id}',[warehousePurchasesController::class,'edit'])->name('warehousePurchases.edit');
    Route::get('admin/warehousePurchases/{id}', [warehousePurchasesController::class, 'show'])->name('warehousePurchases.show');
    Route::put('admin/warehousePurchases/update/{id}',[warehousePurchasesController::class,'update'])->name('warehousePurchases.update');
    Route::delete('admin/warehousePurchases/destroy/{id}',[warehousePurchasesController::class,'destroy'])->name('warehousePurchases.destroy');
    ///////////////////////////
    Route::get('admin/warehousepayment',[warehouse_PaymentController::class,'index'])->name('warehousepayment.index');
    Route::get('admin/warehousepayment/create',[warehouse_PaymentController::class,'create'])->name('warehousepayment.create');
    Route::post('admin/warehousepayment/store',[warehouse_PaymentController::class,'store'])->name('warehousepayment.store');
    Route::get('admin/warehousepayment/edit/{id}',[warehouse_PaymentController::class,'edit'])->name('warehousepayment.edit');
    Route::get('admin/warehousepayment/{id}', [warehouse_PaymentController::class, 'show'])->name('warehousepayment.show');
    Route::put('admin/warehousepayment/update/{id}',[warehouse_PaymentController::class,'update'])->name('warehousepayment.update');
    Route::delete('admin/warehousepayment/destroy/{id}',[warehouse_PaymentController::class,'destroy'])->name('warehousepayment.destroy');
    ///////////////////////////

        
    Route::get('admin/labpayment',[lab_PaymentController::class,'index'])->name('labpayment.index');
    Route::get('admin/labpayment/create',[lab_PaymentController::class,'create'])->name('labpayment.create');
    Route::post('admin/labpayment/store',[lab_PaymentController::class,'store'])->name('labpayment.store');
    Route::get('admin/labpayment/edit/{id}',[lab_PaymentController::class,'edit'])->name('labpayment.edit');
    Route::get('admin/labpayment/{id}', [lab_PaymentController::class, 'show'])->name('labpayment.show');
    Route::put('admin/labpayment/update/{id}',[lab_PaymentController::class,'update'])->name('labpayment.update');
    Route::delete('admin/labpayment/destroy/{id}',[lab_PaymentController::class,'destroy'])->name('labpayment.destroy');
    ///////////////////////////
     
    Route::get('/patients/{id}/statement', [PatientController::class, 'patientStatement'])->name('patients.statement');
    Route::get('/laboratory/{id}/statement', [laboratoryController::class, 'laboratoryStatement'])->name('laboratory.statement');
    Route::get('/storehouse/{id}/statement', [storehouseController::class, 'storehouseStatement'])->name('storehouse.statement');

    ////////////////////
    Route::get('/Report/cash-box', [financial_ReportController::class, 'getCashBox'])->name('Report.getCashBox');
    Route::get('/Report/card-box', [financial_ReportController::class, 'getCardBox'])->name('Report.getCardBox');


    ////////////////////////
     Route::get('laboratory_purchases/{id}/print', [laboratory_purchasesController::class, 'print'])->name('laboratory_purchases.print');

    Route::get('/patients/{id}/statement/pdf', [PatientController::class, 'patientStatementPdf'])->name('patients.statement.pdf');

    
    Route::get('admin/laboratory',[laboratoryController::class,'index'])->name('laboratory.index');
    Route::get('admin/laboratory/create',[laboratoryController::class,'create'])->name('laboratory.create');
    Route::post('admin/laboratory/store',[laboratoryController::class,'store'])->name('laboratory.store');
    Route::get('admin/laboratory/edit/{id}',[laboratoryController::class,'edit'])->name('laboratory.edit');
    Route::put('admin/laboratory/update/{id}',[laboratoryController::class,'update'])->name('laboratory.update');
    Route::delete('admin/laboratory/destroy/{id}',[laboratoryController::class,'destroy'])->name('laboratory.destroy');
    ///////////////////////////
    Route::get('admin/storehouse',[storehouseController::class,'index'])->name('storehouse.index');
    Route::get('admin/storehouse/create',[storehouseController::class,'create'])->name('storehouse.create');
    Route::post('admin/storehouse/store',[storehouseController::class,'store'])->name('storehouse.store');
    Route::get('admin/storehouse/edit/{id}',[storehouseController::class,'edit'])->name('storehouse.edit');
    Route::put('admin/storehouse/update/{id}',[storehouseController::class,'update'])->name('storehouse.update');
    Route::delete('admin/storehouse/destroy/{id}',[storehouseController::class,'destroy'])->name('storehouse.destroy');
    ///////////////////////////
  
});       
    
   

    Route::get('/ajax/patients', [App\Http\Controllers\AjaxController::class, 'searchPatients'])->name('ajax.patients');
    Route::get('/ajax/doctors', [App\Http\Controllers\AjaxController::class, 'searchDoctors'])->name('ajax.doctors');
    // للطباعة
   


    
    /////////////////////////////////
    
   
    
  
    
    
Route::middleware(['CheckRole:admin,secretary'])->group(function () {
        Route::get('admin/patients',[PatientController::class,'index'])->name('admin.patients.index');
        Route::get('admin/patients/create',[PatientController::class,'create'])->name('admin.patients.create');
        Route::post('admin/patients/store',[PatientController::class,'store'])->name('admin.patients.store');
        Route::get('admin/patients/edit/{id}',[PatientController::class,'edit'])->name('admin.patients.edit');
        Route::put('admin/patients/{id}',[PatientController::class,'update'])->name('admin.patients.update');
        Route::get('admin/patients/show/{id}',[PatientController::class,'show'])->name('admin.patients.show');
        Route::delete('admin/patients/destroy/{id}',[PatientController::class,'destroy'])->name('admin.patients.destroy');
        ///////////////////////////
        Route::get('admin/appointments',[AppointmentsController::class,'index'])->name('appointments.index');
        Route::get('admin/appointments/create',[AppointmentsController::class,'create'])->name('appointments.create');
        Route::post('admin/appointments/store',[AppointmentsController::class,'store'])->name('appointments.store');
        Route::get('admin/appointments/edit/{id}',[AppointmentsController::class,'edit'])->name('appointments.edit');
        Route::put('admin/appointments/update/{id}',[AppointmentsController::class,'update'])->name('appointments.update');
        Route::delete('admin/appointments/destroy/{id}',[AppointmentsController::class,'destroy'])->name('appointments.destroy');
        ///////////////////////////
        Route::get('clinic-warehouse', [WarehouseStockController::class, 'index'])->name('clinic_warehouse.index');
        Route::post('clinic-warehouse/{id}/toggle-status', [WarehouseStockController::class, 'toggleStatus'])->name('clinic_warehouse.toggle_status');
        ////////////////////////////////////
        Route::get('admin/patientPayment',[patient_PaymentController::class,'index'])->name('patientPayment.index');
        Route::get('admin/patientPayment/create',[patient_PaymentController::class,'create'])->name('patientPayment.create');
        Route::post('admin/patientPayment/store',[patient_PaymentController::class,'store'])->name('patientPayment.store');
        Route::get('admin/patientPayment/edit/{id}',[patient_PaymentController::class,'edit'])->name('patientPayment.edit');
        Route::get('admin/patientPayment/{id}', [patient_PaymentController::class, 'show'])->name('patientPayment.show');
        Route::put('admin/patientPayment/update/{id}',[patient_PaymentController::class,'update'])->name('patientPayment.update');
        Route::delete('admin/patientPayment/destroy/{id}',[patient_PaymentController::class,'destroy'])->name('patientPayment.destroy');
        ///////////////////////////
        Route::get('admin/services',[ServicesController::class,'index'])->name('admin.services.index');
        Route::get('admin/services/create',[ServicesController::class,'create'])->name('admin.services.create');
        Route::post('admin/services/store',[ServicesController::class,'store'])->name('admin.services.store');
        Route::get('admin/services/edit/{id}',[ServicesController::class,'edit'])->name('admin.services.edit');
        Route::put('admin/services/{id}',[ServicesController::class,'update'])->name('admin.services.update');
        Route::get('admin/services/show/{id}',[ServicesController::class,'show'])->name('admin.services.show');
        Route::delete('admin/services/destroy/{id}',[ServicesController::class,'destroy'])->name('admin.services.destroy');
        ///////////////////////////
        Route::get('admin/sessions',[Clinic_SessionsController::class,'index'])->name('sessions.index');
        Route::get('admin/sessions/create',[Clinic_SessionsController::class,'create'])->name('sessions.create');
        Route::post('admin/sessions/store',[Clinic_SessionsController::class,'store'])->name('sessions.store');
        Route::get('admin/sessions/edit/{id}',[Clinic_SessionsController::class,'edit'])->name('sessions.edit');
        Route::put('admin/sessions/update/{id}',[Clinic_SessionsController::class,'update'])->name('sessions.update');
        Route::get('admin/sessions/show/{id}',[Clinic_SessionsController::class,'show'])->name('sessions.show');
        Route::delete('admin/sessions/destroy/{id}',[Clinic_SessionsController::class,'destroy'])->name('sessions.destroy');
        ///////////////////////////
        Route::get('admin/LaboratoryRequests',[LaboratoryRequestsController::class,'index'])->name('LaboratoryRequests.index');
        Route::get('admin/LaboratoryRequests/create',[LaboratoryRequestsController::class,'create'])->name('LaboratoryRequests.create');
        Route::post('admin/LaboratoryRequests/store',[LaboratoryRequestsController::class,'store'])->name('LaboratoryRequests.store');
        Route::get('admin/LaboratoryRequests/edit/{id}',[LaboratoryRequestsController::class,'edit'])->name('LaboratoryRequests.edit');
        Route::put('admin/LaboratoryRequests/update/{id}',[LaboratoryRequestsController::class,'update'])->name('LaboratoryRequests.update');
        Route::delete('admin/LaboratoryRequests/destroy/{id}',[LaboratoryRequestsController::class,'destroy'])->name('LaboratoryRequests.destroy');
        ///////////////////////////
        Route::get('/Report/patients-debts-summary', [PatientController::class, 'patientsDebtsSummary'])->name('Report.patientsDebtsSummary');

        Route::post('/change-password', [AuthenticatedController::class, 'changePassword']);
        
        Route::get('change-password', function () {
        return view('Admin.change_password');
        })->name('change-password');

    });
        Route::resource('admin',AdminController::class);
        
        // Route::get('change-password')->view('Admin.change_password');
// });

require __DIR__.'/auth.php';
