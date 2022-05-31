<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('update-employee-code', function () {
//    $users =  DB::SELECT('SELECT * FROM `users` where  id in (select user_id from user_units WHERE unit_id = 1)');
//
//    foreach ($users as $key => $user){
//        if (is_numeric($user->employee_code) && substr($user->employee_code, 0, 1) != 0) {
//            $newEmployeeCode = $user->employee_code - 20000000;
//            \App\User::where('id', $user->id)->update([
//                'employee_code' => $newEmployeeCode
//            ]);
//        }
//    }
//
//});
Route::get('test', function (){
    return \App\LeaveType::leaveTypeWiseLeaveAccumulation(11);
});

Route::get('/leaveTypeLimitsForm', function () {
    return view('masterTables.leaveTypeLimitsForm');
})->name('leaveTypeLimitsForm');


//////////////////////////////////////////
Route::get('/', function () {
    return view('loginPage');
})->name('loginPage');

Route::get('forgotPassword', function () {
    return view('forgotPassword');
})->name('forgotPassword');

Route::get('forgot-password/{encryptedToken}','UserController@forgotPasswordForm');
Route::post('reset-password','UserController@resetPassword');


Route::post('employee-type','UserController@empType')->name('employee.type');

Route::get('leaves/creditLeavesCron','LeaveController@creditLeavesCron')->name('leaves.creditLeavesCron');  //CRON
Route::get('leaves/expireCompensatoryLeavesCron','LeaveController@expireCompensatoryLeavesCron')->name('leaves.expireCompensatoryLeavesCron');  //CRON

Route::group(['prefix'=>'employees'], function(){

    Route::post('login','UserController@login')->name('employees.login');
    Route::post('forgotPassword','UserController@forgotPassword')->name('employees.forgotPassword');
    // Route::post('verifyOtp','UserController@verifyOtp')->name('employees.verifyOtp');
    // Route::post('resendOtp','UserController@resendOtp')->name('employees.resendOtp');

    Route::group(['middleware'=>'App\Http\Middleware\RestrictUser'], function(){

        Route::get('myProfile','UserController@myProfile')->name('employees.myProfile');
        Route::get('allMessages','UserController@allMessages')->name('employees.allMessages');
        Route::get('list','UserController@list')->name('employees.list');
        Route::get('retirement-list','UserController@retirementList')->name('employees.retirement.list');
        Route::post('add-retirement','UserController@addRetirement')->name('employees.add.retirement');

        Route::get('changePassword','UserController@changePassword')->name('employees.changePassword');
        Route::post('saveChangePassword','UserController@saveChangePassword')->name('employees.saveChangePassword');
        Route::post('changeProfilePicture','UserController@changeProfilePicture')->name('employees.changeProfilePicture');
        Route::post('checkUnique','UserController@checkUnique')->name('employees.checkUnique');
        Route::post('unitWiseEmployees','UserController@unitWiseEmployees')->name('employees.unitWiseEmployees');
        Route::post('userUnitAndDesignation','UserController@userUnitAndDesignation')->name('employees.userUnitAndDesignation');
        Route::post('othersDashboardDetails','UserController@othersDashboardDetails')->name('employees.othersDashboardDetails');

        Route::post('checkPermission','UserController@checkPermission')->name('employees.checkPermission');

        Route::group(['middleware' => ['permission:reset-password']],function(){
            Route::get('passwordResetRequests','UserController@listPasswordResetRequests')->name('passwordResetRequests.list');
            Route::get('passwordResetRequests/{requestId}','UserController@resetOriginalPassword')->name('passwordResetRequests.resetOriginalPassword');
        });


        Route::group(['middleware' => ['permission:create-user']], function(){

            Route::get('create/{tabName?}','UserController@create')->name('employees.create');
            Route::get('employeeTransfer','UserController@employeeTransfer')->name('employees.employeeTransfer');
            Route::post('saveBasicDetails','UserController@saveBasicDetails')->name('employees.saveBasicDetails');
            Route::post('saveAddressDetails','UserController@saveAddressDetails')->name('employees.saveAddressDetails');
            Route::post('saveTransferDetails','UserController@saveTransferDetails')->name('employees.saveTransferDetails');

        });

        Route::group(['middleware' => ['permission:edit-user']], function(){

            Route::get('edit/{userId}/{tabName?}','UserController@edit')->name('employees.edit');
            Route::get('profile/{userId}','UserController@userProfile')->name('employees.userProfile');
            Route::get('status/{action}/{userId}','UserController@changeUserStatus');
            Route::post('editBasicDetails','UserController@editBasicDetails')->name('employees.editBasicDetails');
            Route::post('editAddressDetails','UserController@editAddressDetails')->name('employees.editAddressDetails');

        });

        Route::group(['middleware' => ['permission:approve-user']], function(){

            Route::get('approveUser/{userId}','UserController@approveUser')->name('employees.approveUser');

        });

        Route::group(['middleware' => ['permission:import-user']], function(){

            Route::get('importUsers','UserController@importUsers')->name('employees.importUsers');
            Route::get('importLeaveAccumulations','UserController@importLeaveAccumulations')->name('employees.importLeaveAccumulations');
            Route::post('importUserFile','UserController@importUserFile')->name('employees.importUserFile');
            Route::post('importAccumulationFile','UserController@importAccumulationFile')->name('employees.importAccumulationFile');

        });

        Route::get('myProfile/{tabName?}','UserController@myProfile')->name('employees.myProfile');
        Route::post('deleteMessages','UserController@deleteMessages')->name('employees.deleteMessages');
        Route::post('unreadMessages','UserController@unreadMessages')->name('employees.unreadMessages');
        Route::get('dashboard','UserController@dashboard')->name('employees.dashboard');
        Route::get('logout','UserController@logout')->name('employees.logout');
        Route::get('list','UserController@list')->name('employees.list');
    });  //RestrictUser Middleware Group

}); //prefix employees Group

Route::group(['prefix'=>'masterTables','middleware'=>'App\Http\Middleware\RestrictUser'], function(){

    Route::group(['middleware'=>['permission:manage-masterTable']],function(){

        Route::get('list','MasterTableController@list')->name('masterTables.list');

        Route::get('designations','MasterTableController@listDesignations')->name('masterTables.listDesignations');
        Route::get('designations/{action}/{designationId?}','MasterTableController@designationAction')->name('masterTables.designationAction');
        Route::post('saveDesignation','MasterTableController@saveDesignation')->name('masterTables.saveDesignation');

        Route::get('departments','MasterTableController@listDepartments')->name('masterTables.listDepartments');
        Route::get('departments/{action}/{departmentId?}','MasterTableController@departmentAction')->name('masterTables.departmentAction');
        Route::post('saveDepartment','MasterTableController@saveDepartment')->name('masterTables.saveDepartment');

        Route::get('qualifications','MasterTableController@listQualifications')->name('masterTables.listQualifications');
        Route::get('qualifications/{action}/{qualificationId?}','MasterTableController@qualificationAction')->name('masterTables.qualificationAction');
        Route::post('saveQualification','MasterTableController@saveQualification')->name('masterTables.saveQualification');

        Route::get('sessions','MasterTableController@listSessions')->name('masterTables.listSessions');
        Route::get('sessions/{action}/{sessionId?}','MasterTableController@sessionAction')->name('masterTables.sessionAction');
        Route::post('saveSession','MasterTableController@saveSession')->name('masterTables.saveSession');

        Route::get('units','MasterTableController@listUnits')->name('masterTables.listUnits');
        Route::get('units/{action}/{unitId?}','MasterTableController@unitAction')->name('masterTables.unitAction');
        Route::post('saveUnit','MasterTableController@saveUnit')->name('masterTables.saveUnit');

        Route::get('holidayList/{unitId?}','MasterTableController@listHolidays')->name('masterTables.listHolidays');
        Route::get('holidays/{action}/{holidayId?}','MasterTableController@holidayAction')->name('masterTables.holidayAction');
        Route::post('saveHoliday','MasterTableController@saveHoliday')->name('masterTables.saveHoliday');


        Route::get('import/holidays/','MasterTableController@importHoliday')->name('masterTables.import_holiday');
        Route::post('import-save/holidays','MasterTableController@importHolidaySave')->name('masterTables.import_holiday_save');

    }); //masterTable middleware Group

}); //prefix masterTables Group

Route::group(['prefix'=>'leaves','middleware'=>'App\Http\Middleware\RestrictUser'], function(){

    Route::group(['prefix'=>'call-of-extra-leaves'], function() {
        Route::get('/','CallOfExtraDutyController@index')->name('leaves.listCallOfExtraDutyLeaves');
        Route::get('/create','CallOfExtraDutyController@create')->name('leaves.callOfExtraDuty.create');

        Route::post('/store','CallOfExtraDutyController@store')->name('leaves.callOfExtraDuty.store');

        Route::get('/approvals','CallOfExtraDutyController@listApprovals')->name('leaves.callOfExtraDuty.approvals');
        Route::post('/save-approval','CallOfExtraDutyController@saveApproval')->name('leaves.callOfExtraDuty.save.approval');
        Route::post('verification-messages','CallOfExtraDutyController@verificationMessages')->name('leaves.callOfExtraDuty.verificationMessages');
    });

    Route::post('leaveTypeWiseLeaveAccumulation','LeaveController@leaveTypeWiseLeaveAccumulation')->name('leaves.leaveTypeWiseLeaveAccumulation');
    Route::post('holidaysBetweenLeaves','LeaveController@holidaysBetweenLeaves')->name('leaves.holidaysBetweenLeaves');
    Route::post('appliedLeaveApprovalMessages','LeaveController@appliedLeaveApprovalMessages')->name('leaves.appliedLeaveApprovalMessages');
    Route::post('additionalAppliedLeaveDetails','LeaveController@additionalAppliedLeaveDetails')->name('leaves.additionalAppliedLeaveDetails');

    Route::post('compensatoryLeaveVerificationMessages','LeaveController@compensatoryLeaveVerificationMessages')->name('leaves.compensatoryLeaveVerificationMessages');

    Route::get('applyLeave','LeaveController@applyLeave')->name('leaves.applyLeave');
    Route::post('saveAppliedLeave','LeaveController@saveAppliedLeave')->name('leaves.saveAppliedLeave');
    Route::get('appliedLeaves','LeaveController@listAppliedLeaves')->name('leaves.listAppliedLeaves');

    Route::get('holidays/{sessionId?}','LeaveController@listHolidays')->name('leaves.listHolidays');
    Route::get('downloadLeaveDocuments/{documentName}','LeaveController@downloadLeaveDocuments')->name('leaves.downloadLeaveDocuments');
    Route::get('compensatoryLeaves','LeaveController@listCompensatoryLeaves')->name('leaves.listCompensatoryLeaves');

    Route::get('compensatoryLeaves/{action}','LeaveController@compensatoryLeaveAction')->name('leaves.compensatoryLeaveAction');
    Route::post('saveCompensatoryLeave','LeaveController@saveCompensatoryLeave')->name('leaves.saveCompensatoryLeave');
    Route::get('cancelAppliedLeave/{appliedLeaveid}','LeaveController@cancelAppliedLeave')->name('leaves.cancelAppliedLeave');

    Route::get('compensatoryLeaves/{compensatoryLeave}/document-download','LeaveController@downloadCompensatoryDocument')->name('leaves.compensatory.document');


    Route::group(['middleware'=>['permission:approve-leave|verify-attendance']], function(){
        Route::get('appliedLeaveApprovals/{leaveStatus?}','LeaveController@listAppliedLeaveApprovals')->name('leaves.listAppliedLeaveApprovals');
        Route::post('saveAppliedLeaveApproval','LeaveController@saveAppliedLeaveApproval')->name('leaves.saveAppliedLeaveApproval');
        Route::get('compensatoryLeaveApprovals','LeaveController@listCompensatoryLeaveApprovals')->name('leaves.listCompensatoryLeaveApprovals');
        Route::post('saveCompensatoryLeaveApproval','LeaveController@saveCompensatoryLeaveApproval')->name('leaves.saveCompensatoryLeaveApproval');
    });

    Route::group(['middleware'=>['permission:generate-leaveReport']], function(){
        Route::post('generateLeaveReport','LeaveController@generateLeaveReport')->name('leaves.generateLeaveReport');

        Route::get('leaveReport','LeaveController@leaveReportForm')->name('leaves.leaveReportForm');

        // Route::get('pendingLeaveReport','LeaveController@pendingLeaveReport')->name('leaves.pendingLeaveReport');
        Route::get('generateLeaveReport/{leaveStatus?}','LeaveController@generateLeaveReport')->name('leaves.generateLeaveReport/pending');
        // Route::get('rejectLeaveReport','LeaveController@rejectLeaveReport')->name('leaves.rejectLeaveReport');
        // Route::post('rejectGenerateLeaveReport','LeaveController@rejectGenerateLeaveReport')->name('leaves.rejectGenerateLeaveReport');

    });

    Route::group(['middleware' => ['permission:create-user']], function(){

        Route::get('leaveAccumulationsForm','LeaveController@leaveAccumulationsForm')->name('leaves.leaveAccumulationsForm');
        Route::post('employeeLeaveAccumulations','LeaveController@employeeLeaveAccumulations')->name('leaves.employeeLeaveAccumulations');
        Route::post('saveEmployeeAccumulation','LeaveController@saveEmployeeAccumulation')->name('leaves.saveEmployeeAccumulation');

    });


    Route::get('add-leave','AddLeaveController@addLeave')->name('leaves.addLeave');
    Route::post('save-leave','AddLeaveController@saveLeave')->name('leaves.saveLeave');

}); //prefix leaves Group
