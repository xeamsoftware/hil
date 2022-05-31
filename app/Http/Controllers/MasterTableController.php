<?php

namespace App\Http\Controllers;

use App\Helpers\VisualHelper;
use App\Imports\ImportHoliday;
use Illuminate\Http\Request;
use Auth;
use App\Qualification;
use App\Session;
use App\Unit;
use App\Holiday;
use App\Designation;
use App\Department;
use Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MasterTableController extends Controller
{
    /*
        Get the list of mastertables
    */
    public function list()
    {
        $data = [
            'Departments' => 'departments',
            'Designations' => 'designations',
            'Qualifications' => 'qualifications',
            'Sessions' => 'sessions',
            'Units' => 'units',
            'Holidays' => 'holidayList'
        ];

        ksort($data);

        return view('masterTables.list')->with(['data'=>$data]);
    }

    /*
        Get all designations & show them on a datatable
    */
    public function listDesignations()
    {
        $designations = Designation::get();

        return view('masterTables.listDesignations')->with(['designations'=>$designations]);
    }

    /*
        Take appropriate action in response to the action selected by a user
    */
    public function designationAction($action,$designationId = null)
    {
        $data['action'] = $action;

        if(!empty($designationId)){
            $designation = Designation::find($designationId);
        }

        if($action == 'edit'){
            $data['designation'] = $designation;

        }elseif($action == 'activate'){
            $designation->status = '1';
            $designation->save();

            return redirect()->route('masterTables.listDesignations');

        }elseif($action == 'deactivate'){
            $designation->status = '0';
            $designation->save();

            return redirect()->route('masterTables.listDesignations');
        }

        return view('masterTables.designationForm')->with(['data'=>$data]);
    }

    /*
        Create a new designation or update an existing one
    */
    public function saveDesignation(Request $request)
    {
        $request->validate([

            'designationName' => 'bail|required|unique:designations,name|max:30',

        ]);

        if($request->action == 'add'){
            $data = ['name' => $request->designationName,'status'=>'1'];

            Designation::create($data);
        }else{

            $designation = Designation::find($request->designationId);

            $designation->name = $request->designationName;
            $designation->save();

        }

        return redirect()->route('masterTables.listDesignations');
    }

    /*
        Get all departments from the database & show them on a datatable
    */
    public function listDepartments()
    {
        $departments = Department::get();

        return view('masterTables.listDepartments')->with(['departments'=>$departments]);
    }

    /*
        Take appropriate action in response to the action selected by a user
    */
    public function departmentAction($action,$departmentId = null)
    {
        $data['action'] = $action;

        if(!empty($departmentId)){
            $department = Department::find($departmentId);
        }

        if($action == 'edit'){
            $data['department'] = $department;

        }elseif($action == 'activate'){
            $department->status = '1';
            $department->save();

            return redirect()->route('masterTables.listDepartments');

        }elseif($action == 'deactivate'){
            $department->status = '0';
            $department->save();

            return redirect()->route('masterTables.listDepartments');
        }

        return view('masterTables.departmentForm')->with(['data'=>$data]);
    }

    /*
        Create a new department or update an existing one
    */
    public function saveDepartment(Request $request)
    {
        $request->validate([

            'departmentName' => 'bail|required|unique:departments,name|max:30',

        ]);

        if($request->action == 'add'){
            $data = ['name' => $request->departmentName,'status'=>'1'];

            Department::create($data);
        }else{

            $department = Department::find($request->departmentId);

            $department->name = $request->departmentName;
            $department->save();

        }

        return redirect()->route('masterTables.listDepartments');
    }

    /*
        Get all qualifications & show them on a datatable
    */
    public function listQualifications()
    {
        $qualifications = Qualification::get();

        return view('masterTables.listQualifications')->with(['qualifications'=>$qualifications]);
    }

    /*
        Take appropriate action in response to the action selected by a user
    */
    public function qualificationAction($action,$qualificationId = null)
    {
        $data['action'] = $action;

        if(!empty($qualificationId)){
            $qualification = Qualification::find($qualificationId);
        }

        if($action == 'edit'){
            $data['qualification'] = $qualification;

        }elseif($action == 'activate'){
            $qualification->status = '1';
            $qualification->save();

            return redirect()->route('masterTables.listQualifications');

        }elseif($action == 'deactivate'){
            $qualification->status = '0';
            $qualification->save();

            return redirect()->route('masterTables.listQualifications');
        }

        return view('masterTables.qualificationForm')->with(['data'=>$data]);
    }

    /*
        Create a new qualification or update an existing one
    */
    public function saveQualification(Request $request)
    {

        if($request->action == 'add'){

            $request->validate([
                'qualificationEnglishName' => 'bail|required|unique:qualifications,name|max:30',
                'qualificationHindiName' => 'bail|required|unique:qualifications,hindi_name|max:30',
            ]);

            $data = [
                'name' => $request->qualificationEnglishName,
                'hindi_name' => $request->qualificationHindiName,
                'status' => '1'
            ];

            Qualification::create($data);

        }else{

            $request->validate([
                'qualificationEnglishName' => 'bail|required|max:30',
                'qualificationHindiName' => 'bail|required|max:30',
            ]);

            $qualification = Qualification::find($request->qualificationId);

            $qualification->name = $request->qualificationEnglishName;
            $qualification->hindi_name = $request->qualificationHindiName;

            $qualification->save();

        }

        return redirect()->route('masterTables.listQualifications');
    }

    /*
        Get all sessions from the database & show them on a datatable
    */
    public function listSessions()
    {
        $sessions = Session::get();

        return view('masterTables.listSessions')->with(['sessions' => $sessions]);
    }

    /*
        Take appropriate action in response to the action selected by a user
    */
    public function sessionAction($action, $sessionId = null)
    {
        $data['action'] = $action;

        if(!empty($sessionId)){
            $session = Session::find($sessionId);
        }

        if($action == 'edit'){
            $data['session'] = $session;

        }elseif($action == 'activate'){
            $session->status = '1';
            $session->save();

            return redirect()->route('masterTables.listSessions');

        }elseif($action == 'deactivate'){
            $session->status = '0';
            $session->save();

            return redirect()->route('masterTables.listSessions');

        }

        return view('masterTables.sessionForm')->with(['data'=>$data]);
    }

    /*
        Create a new session or update an existing one
    */
    public function saveSession(Request $request)
    {
        $request->validate([
            'sessionName' => 'bail|required|unique:sessions,name|max:30'
        ]);

        if($request->action == 'add'){
            $data = ['name'=>$request->sessionName, 'status'=>'1'];

            Session::create($data);

        }else{
            $session = Session::find($request->sessionId);

            $session->name = $request->sessionName;
            $session->save();
        }

        return redirect()->route('masterTables.listSessions');
    }

    /*
        Get all units from database & show them on a datatable
    */
    public function listUnits()
    {
        $units = Unit::get();

        return view('masterTables.listUnits')->with(['units'=>$units]);
    }

    /*
        Take appropriate action in response to the action selected by a user
    */
    public function unitAction($action, $unitId = null)
    {
        $data['action'] = $action;

        if(!empty($unitId)){
            $unit = Unit::find($unitId);
        }

        if($action == 'edit'){
            $data['unit'] = $unit;

        }elseif($action == 'activate'){
            $unit->status = '1';
            $unit->save();

            return redirect()->route('masterTables.listUnits');

        }elseif($action == 'deactivate'){
            $unit->status = '0';
            $unit->save();

            return redirect()->route('masterTables.listUnits');

        }

        return view('masterTables.unitForm')->with(['data'=>$data]);
    }

    /*
        Create a new unit or update an existing one
    */
    public function saveUnit(Request $request)
    {
        $request->validate([
            'unitName' => 'bail|required|unique:units,name|max:30',
        ]);

        if($request->action == 'add'){

            $data = ['name'=>$request->unitName,'status'=>'1'];

            Unit::create($data);

        }else{

            $unit = Unit::find($request->unitId);

            $unit->name = $request->unitName;
            $unit->save();
        }

        return redirect()->route('masterTables.listUnits');
    }

    /*
        Get all holidays of a unit & show them on a datatable
    */
    public function listHolidays($unitId = null)
    {
        $user = Auth::user();
        $units = Unit::get();

        if(empty($unitId)){
            $unit = Unit::find(1);
        }else{
            $unit = Unit::find($unitId);
        }

        $unitName = $unit->name;
        $holidays = Holiday::where(['unit_id'=>$unit->id])->orderBy('from_date','DESC')->get();

        return view('masterTables.listHolidays')->with(['holidays'=>$holidays,'units'=>$units,'unitName'=>$unitName]);
    }

    /*
        Take appropriate action in response to the action selected by a user
    */
    public function holidayAction($action, $holidayId = null)
    {
        $data['action'] = $action;
        $data['sessions'] = Session::where(['status'=>'1'])->get();
        $data['units'] = Unit::where(['status'=>'1'])->get();

        if(!empty($holidayId)){
            $holiday = Holiday::find($holidayId);
        }

        if($action == 'edit'){
            $data['holiday'] = $holiday;
        }elseif($action == 'activate'){
            $holiday->status = '1';
            $holiday->save();

            return redirect()->route('masterTables.listHolidays');

        }elseif($action == 'deactivate'){
            $holiday->status = '0';
            $holiday->save();

            return redirect()->route('masterTables.listHolidays');

        }

        return view('masterTables.holidayForm')->with(['data'=>$data]);
    }

    /*
        Create a new holiday or update an existing one
    */
    public function saveHoliday(Request $request)
    {
        $request->validate([
            'sessionName' => 'bail|required',
            'fromDate' => 'required_if:action,==,add',
            'toDate' => 'required_if:action,==,add',
            'description' => 'bail|required',
            'holidayName' => 'bail|required',
            'unitName' => 'bail|required'
        ]);

        $data = [
            'description' => $request->description,
            'status' => '1'
        ];

        if($request->action == 'add'){
            $data['from_date'] = date("Y-m-d",strtotime($request->fromDate));
            $data['to_date'] = date("Y-m-d",strtotime($request->toDate));

            Holiday::firstOrCreate(
                [
                    'name' => $request->holidayName,
                    'session_id' => $request->sessionName,
                    'unit_id' => $request->unitName,
                    'holiday_type' => $request->holiday_type
                ],
                $data
            );

        }else{

            $holiday = Holiday::find($request->holidayId);
            $data['name'] = $request->holidayName;
            $data['session_id'] = $request->sessionName;
            $data['unit_id'] = $request->unitName;
            $data['holiday_type'] = $request->holiday_type;

            $holiday->update($data);
        }

        return redirect()->route('masterTables.listHolidays');
    }

    function importHoliday(){
        $data['sessions'] = Session::where(['status'=>'1'])->get();
        $data['units'] = Unit::where(['status'=>'1'])->get();
        return view('masterTables.import_holiday', compact('data'));
    }


    public function importHolidaySave(Request $request)
    {
        $request->validate([
            'sessionName' => 'bail|required',
            'unitName' => 'bail|required'
        ]);

        $unitId = $request->unitName;
        $sessionId = $request->sessionName;

        $data = Excel::toArray(new ImportHoliday(), $request->file('holiday_file'));

        if(count($data[0][0]) > 2){
            return back()->with('error', 'Wrong Template Upload');
        }
        foreach ($data[0] as $key => $holidayData){
            if($key!=0) {
                try {
                    $holidayName = $holidayData[0];
                    $holidayDate = $holidayData[1];
                    if(is_int($holidayDate) || is_float($holidayDate)) {
                        if ((bool)strtotime($holidayDate) == false) {
                            return back()->with('error', 'Some Extra Data added in date of holiday name '.$holidayName.' kindly remove date from record and write it again.');
                        }
                    }
                    if(!isset($holidayDate) || $holidayDate == ''){
                        return back()->with('error', 'Missing Holiday Date');
                    }
                    $repairedDate = VisualHelper::repairDate($holidayDate);
                    if (isset($repairedDate['error'])) {
                        return back()->with('error', $repairedDate['error']);
                    }
                    $holidayDate = $repairedDate['date'];

                    $holiday = Holiday::where(['unit_id' => $unitId, 'session_id' => $sessionId, 'from_date' => $holidayDate])->first();
                    $inputArray = [
                        'name' => $holidayName,
                        'session_id' => $sessionId,
                        'unit_id' => $unitId,
                        'from_date' => $holidayDate,
                        'to_date' => $holidayDate,
                        'description' => $holidayName,
                        'status' => '1'
                    ];
                    if (isset($holiday)) {
                        $holiday->update($inputArray);
                    } else {
                        Holiday::firstOrCreate($inputArray);
                    }
                } catch (\Exception $e) {
                    return $holidayName;
                }

            }
        }


        return redirect()->route('masterTables.listHolidays');
    }


}//end class
