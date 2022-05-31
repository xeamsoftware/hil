@extends('admins.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">
        Employee Registration Page
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
     @php $lastInsertedEmployee = session('lastInsertedEmployee');
          if(empty($lastInsertedEmployee)){
            $lastInsertedEmployee = 0;
          }
     @endphp
    <!-- Main content -->
    <section class="content">
      <!-- Main row starts here -->
      <div class="row">
        <!-- col starts here -->
        <div class="pad margin no-print">
          <div class="callout callout-danger" style="margin-bottom: 0!important;">
            <span><strong>Note:&nbsp;&nbsp;</strong></span>
            <span><em>Only the asterisk(*) marked fields are required. You can leave the rest or fill them if required.</em></span>
          </div>
        </div>
        <div class="col-md-12">
        	          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs edit-nav-styling">
              <li id="basicDetailsTab" class=""><a href="#tab_basicDetailsTab" data-toggle="tab">@lang('createUserForm.hi.basicDetail') / @lang('createUserForm.en.basicDetail')</a></li>
              <li id="addressDetailsTab" class=""><a href="#tab_addressDetailsTab" data-toggle="tab">@lang('createUserForm.hi.addressDetail') / @lang('createUserForm.en.addressDetail')</a></li>
            </ul>
            <div class="tab-content">

            											<!-- Basic details starts here -->
              <div class="tab-pane active" id="tab_basicDetailsTab">
              @if($errors->basic->any())
                <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-1">

                  <button type="button" class="close login-alert-close" data-dismiss="alert" aria-hidden="true">×</button>

                    <h4 class="login-error-list">Error</h4>
                    <ul class="login-alert2">

                        @foreach ($errors->basic->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>  
              @endif          
              <!-- form start -->
              <form id="basicDetailsForm" action="{{route('employees.saveBasicDetails')}}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                  <div class="form-group">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label" for="firstName">@lang('createUserForm.hi.firstName') / @lang('createUserForm.en.firstName')<span class="required">*</span></label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="firstName" id="firstName" placeholder="Enter first name">
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="middleName">@lang('createUserForm.hi.middleName') / @lang('createUserForm.en.middleName')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="middleName" id="middleName" placeholder="Enter middle name">
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="lastName">@lang('createUserForm.hi.lastName') / @lang('createUserForm.en.lastName')<span class="required">*</span></label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="lastName" id="lastName" placeholder="Enter last name">
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="fatherName">@lang('createUserForm.hi.fatherName') / @lang('createUserForm.en.fatherName')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="fatherName" id="fatherName" placeholder="Enter father's name">
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="motherName">@lang('createUserForm.hi.motherName') / @lang('createUserForm.en.motherName')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="motherName" id="motherName" placeholder="Enter mother's name">
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="dateOfBirth">@lang('createUserForm.hi.dateOfBirth') / @lang('createUserForm.en.dateOfBirth')</label>
                                    <div class="input-group date single-input-lbl">
                                      <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                        <input type="text" class="form-control pull-right date-input input-sm basic-detail-input-style" name="dateOfBirth" id="dateOfBirth" placeholder="Please select date of birth date" readonly>
                                        <span class="dateOfBirthErrors"></span>
                                    </div>
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="panNumber">@lang('createUserForm.hi.panNumber') / @lang('createUserForm.en.panNumber')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-uppercase" name="panNumber" id="panNumber" placeholder="Enter PAN number">
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="adhaarNumber">@lang('createUserForm.hi.adhaarNumber') / @lang('createUserForm.en.adhaarNumber')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="adhaarNumber" id="adhaarNumber" placeholder="Enter adhaar number">
                              </div>
                              <div class="form-group">
                                <label for="qualificationId" class="control-label">@lang('createUserForm.hi.qualificationId') / @lang('createUserForm.en.qualificationId')<span class="required">*</span></label>
                                  <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="qualificationId" id="qualificationId">
                                    <option value="" selected disabled>Please Select Employee's qualification</option>
                                    @if(!$data['qualifications']->isEmpty())
                                      @foreach($data['qualifications'] as $key => $qualification)
                                        <option value="{{$qualification->id}}">{{$qualification->hindi_name}} / {{$qualification->name}}</option>
                                      @endforeach
                                    @endif
                                  </select>  
                              </div>
                              <div class="form-group">
                                <label for="gender" class="control-label">@lang('createUserForm.hi.gender') / @lang('createUserForm.en.gender')<span class="required">*</span></label>
                                  <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="gender" id="gender">
                                    <option value="" selected disabled>Please Select Employee's gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="bloodGroupId">@lang('createUserForm.hi.bloodGroupId') / @lang('createUserForm.en.bloodGroupId')</label>
                                    <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="bloodGroupId" id="bloodGroupId">
                                      <option value="" selected disabled>Please Select Employee's blood group</option>
                                      <option value="A−">A−</option>
                                      <option value="A+">A+</option>
                                      <option value="B−">B−</option>
                                      <option value="B+">B+</option>
                                      <option value="AB−">AB−</option>
                                      <option value="AB+">AB+</option>
                                      <option value="O−">O−</option>
                                      <option value="O+">O+</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="personalMobileNumber">@lang('createUserForm.hi.mobileNumberPersonal') / @lang('createUserForm.en.mobileNumberPersonal')<span class="required">*</span></label>
                                    <input type="text" class="form-control checkUnique single-input-lbl input-sm basic-detail-input-style" name="personalMobileNumber" id="personalMobileNumber" placeholder="Enter mobile number personal">
                                    <span class="personalMobileNumberError"></span>
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="personalEmail">@lang('createUserForm.hi.emailPersonal') / @lang('createUserForm.en.emailPersonal')</label>
                                    <input type="text" class="form-control checkUnique single-input-lbl input-sm basic-detail-input-style" name="personalEmail" id="personalEmail" placeholder="Enter your email">
                                    <span class="personalEmailError"></span>
                              </div>
                              <div class="form-group">
                                <label for="maritalStatus" class="control-label">@lang('createUserForm.hi.maritalStatus') / @lang('createUserForm.en.maritalStatus')<span class="required">*</span></label>
                                  <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="maritalStatus" id="maritalStatus">
                                    <option value="" selected disabled>Please Select marital status</option>
                                    <option value="Married">Married</option>
                                    <option value="Unmarried">Unmarried</option>
                                    <option value="Widowed">Widowed</option>
                                  </select>
                              </div>
                              <div class="form-group spouseDiv">
                                  <label class="control-label" for="spouseName">@lang('createUserForm.hi.spouseName') / @lang('createUserForm.en.spouseName')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="spouseName" id="spouseName" placeholder="Enter wife/ husband's name">
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="emergencyContactName">@lang('createUserForm.hi.emergencyContactName') / @lang('createUserForm.en.emergencyContactName')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="emergencyContactName" id="emergencyContactName" placeholder="Enter Emergency Contact Name">
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="emergencyContactNumber">@lang('createUserForm.hi.contactNoEmergency') / @lang('createUserForm.en.contactNoEmergency')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="emergencyContactNumber" id="emergencyContactNumber" placeholder="Enter your number">
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="relationship">@lang('createUserForm.hi.relationshipId') / @lang('createUserForm.en.relationshipId')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="relationship" id="relationship" placeholder="Enter relationship">
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label" for="vehicleNumber">@lang('createUserForm.hi.vehicleNumber') / @lang('createUserForm.en.vehicleNumber') :</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-uppercase" name="vehicleNumber" id="vehicleNumber" placeholder="Enter vehicle number">
                              </div>


                              <div class="form-group">
                                <label for="unitId" class="control-label">@lang('createUserForm.hi.unitId') / @lang('createUserForm.en.unitId')<span class="required">*</span></label>
                                  <select class="form-control select2 single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="unitIds[]" id="unitIds" multiple="multiple" style="width: 100%">
                                    @if(!$data['units']->isEmpty())
                                      @foreach($data['units'] as $key => $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                      @endforeach
                                    @endif
                                  </select>   
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="employeeType">@lang('createUserForm.hi.employeeType') / @lang('createUserForm.en.employeeType')<span class="required">*</span></label>
                                    <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="employeeType" id="employeeType">
                                      <option value="" selected disabled>Please Select Employee's Type</option>
                                      <option value="Workman">Workman</option>
                                      <option value="M&S">M & S</option>
                                      <option value="CMD">CMD</option>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="employeeCode">@lang('createUserForm.hi.employeeCode') / @lang('createUserForm.en.employeeCode')<span class="required">*</span></label>
                                    <input type="text" class="form-control checkUnique single-input-lbl input-sm basic-detail-input-style" name="employeeCode" id="employeeCode" placeholder="Eg : 615">
                                    <span class="employeeCodeError"></span>
                              </div>

                              <div class="form-group">
                                <label for="designationId" class="control-label">@lang('createUserForm.hi.designationId') / @lang('createUserForm.en.designationId')<span class="required">*</span></label>
                                  <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="designationId" id="designationId">
                                    <option value="" selected disabled>Please Select Employee's Designation</option>
                                    @if(!$data['designations']->isEmpty())
                                      @foreach($data['designations'] as $key => $designation)
                                          <option value="{{$designation->id}}">{{$designation->name}}</option>
                                      @endforeach
                                    @endif
                                  </select>  
                              </div>

                              <div class="form-group">
                                <label for="departmentId" class="control-label">@lang('createUserForm.hi.departmentId') / @lang('createUserForm.en.departmentId')<span class="required">*</span></label>
                                  <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="departmentId" id="departmentId">
                                    <option value="" selected disabled>Please Select Employee's Department</option>
                                    @if(!$data['departments']->isEmpty())
                                      @foreach($data['departments'] as $key => $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                      @endforeach
                                    @endif
                                  </select>  
                              </div>

                              <div class="form-group">
                                <label for="roleId" class="control-label">@lang('createUserForm.hi.roleId') / @lang('createUserForm.en.roleId')<span class="required">*</span></label>
                                  <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="roleId" id="roleId">
                                    <option value="" selected disabled>Please Select Employee's Role</option>
                                    @if(!$data['roles']->isEmpty())
                                      @foreach($data['roles'] as $key => $role)
                                        @if($role->id != 1)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                        @endif
                                      @endforeach
                                    @endif
                                  </select>  
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="officialMobileNumber">@lang('createUserForm.hi.mobileNumberOfficial') / @lang('createUserForm.en.mobileNumberOfficial')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="officialMobileNumber" id="officialMobileNumber" placeholder="Enter mobile number">
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="officialEmail">@lang('createUserForm.hi.emailOfficial') / @lang('createUserForm.en.emailOfficial')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="officialEmail" id="officialEmail" placeholder="Enter your official email id">
                              </div>
                              <div class="form-group">
                                <label for="permissionIds" class="control-label">@lang('createUserForm.hi.permissionIds') / @lang('createUserForm.en.permissionIds')<span class="required">*</span></label>
                                  <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="permissionIds[]" id="permissionIds" multiple="multiple" style="width: 100%">
                                    @if(!empty($data['permissions']))
                                      @foreach($data['permissions'] as $key => $value)
                                        <option value="{{$key}}">{{$key}}</option>
                                      @endforeach
                                    @endif
                                  </select>  
                              </div>
                              <div class="form-group">
                                  <label class="control-label" for="dateOfJoining">@lang('createUserForm.hi.dateOfJoining') / @lang('createUserForm.en.dateOfJoining')<span class="required">*</span></label>
                                    <div class="input-group date single-input-lbl">
                                      <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                        <input type="text" class="form-control pull-right date-input input-sm basic-detail-input-style" name="dateOfJoining" id="dateOfJoining" placeholder="Please Select date of joining date" readonly>
                                    </div>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="supervisor">@lang('createUserForm.hi.supervisor') / @lang('createUserForm.en.supervisor') <span class="required">*</span></label>
                                    <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="supervisor" id="supervisor">
                                    <option value="" selected disabled>Please Select Employee</option>  
                                    @if(!empty($data['allEmployees']))
                                      @foreach($data['allEmployees'] as $key => $value)
                                        <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select> 
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="otherSupervisor">@lang('createUserForm.hi.otherSupervisor') / @lang('createUserForm.en.otherSupervisor') </label>
                                    <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="otherSupervisor" id="otherSupervisor">
                                    <option value="" selected disabled>Please Select Employee</option>  
                                    @if(!empty($data['allEmployees']))
                                      @foreach($data['allEmployees'] as $key => $value)
                                        <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select> 
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="deputyHodId">@lang('createUserForm.hi.dyHod') / @lang('createUserForm.en.dyHod')</label>
                                    <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="deputyHodId" id="deputyHodId">
                                    <option value="" selected disabled>Please Select Employee</option>
                                    @if(!empty($data['allEmployees']))
                                      @foreach($data['allEmployees'] as $key => $value)
                                        <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="hodId">@lang('createUserForm.hi.hod') / @lang('createUserForm.en.hod')</label>
                                    <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="hodId" id="hodId">
                                    <option value="" selected disabled>Please Select Employee</option>
                                    @if(!empty($data['allEmployees']))
                                      @foreach($data['allEmployees'] as $key => $value)
                                        <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="dgmId">@lang('createUserForm.hi.dgm') / @lang('createUserForm.en.dgm')</label>
                                    <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="dgmId" id="dgmId">
                                      <option value="" selected disabled>Please Select DGM</option>
                                      @if(!$data['dgms']->isEmpty())
                                      @foreach($data['dgms'] as $key => $dgm)
                                        <option value="{{$dgm->id}}">{{$dgm->first_name}} {{@$dgm->middle_name}} {{@$dgm->last_name}} ({{@$dgm->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="gmId">@lang('createUserForm.hi.gm') / @lang('createUserForm.en.gm')</label>
                                    <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="gmId" id="gmId">
                                      <option value="" selected disabled>Please Select GM</option>
                                      @if(!$data['gms']->isEmpty())
                                      @foreach($data['gms'] as $key => $gm)
                                        <option value="{{$gm->id}}">{{$gm->first_name}} {{@$gm->middle_name}} {{@$gm->last_name}} ({{@$gm->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="cmdId">@lang('createUserForm.hi.cmd') / @lang('createUserForm.en.cmd')</label>
                                    <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="cmdId" id="cmdId">
                                      <option value="" selected disabled>Please Select CMD</option>
                                      @if(!$data['cmds']->isEmpty())
                                      @foreach($data['cmds'] as $key => $cmd)
                                        <option value="{{$cmd->id}}">{{$cmd->first_name}} {{@$cmd->middle_name}} {{@$cmd->last_name}} ({{@$cmd->employee_code}})</option>
                                      @endforeach
                                    @endif
                                  </select>
                              </div>
                          </div>
                      </div>
                  </div>
                    
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="button" class="btn btn-primary basicDetailsFormSubmit submit-btn-style">Submit</button>
                </div>
              </form>              
              </div>
              												<!-- Basic details ends here -->


              											   <!-- Address details starts here -->

            <div class="tab-pane" id="tab_addressDetailsTab">
              @if($errors->address->any())
                <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-1">

                  <button type="button" class="close login-alert-close" data-dismiss="alert" aria-hidden="true">×</button>

                    <h4 class="login-error-list">Error</h4>
                    <ul class="login-alert2">

                        @foreach ($errors->address->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>  
              @endif

              @if(session()->has('basicSuccess'))
                <div class="alert alert-success alert-dismissible login-alerts-change-pswrd-2">
                  <h4 class="login-error-list">Success</h4>
                    <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('basicSuccess') }}
                </div>
              @elseif(session()->has('addressSuccess')) 
                <div class="alert alert-success alert-dismissible login-alerts-change-pswrd-2">
                  <h4 class="login-error-list">Success</h4>
                    <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('addressSuccess') }}
                </div> 
              @elseif(session()->has('addressError'))  
                <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-2">
                  <h4 class="login-error-list">Error</h4>
                    <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('addressError') }}
                </div>
              @endif  

              <div id="noBasicProfileError" class="alert alert-danger alert-dismissible login-alerts-change-pswrd-2">
                <h4 class="login-error-list">Error</h4>
                  <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                  {{"Please fill the basic details form for a new employee, or you can edit the profile of an existing user later."}}
              </div>

              <form id="addressDetailsForm" action="{{route('employees.saveAddressDetails')}}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                  <div class="form-group">
                      <div class="row">
                          <div class="col-md-6">
                          	<p class="currentAddress" id="currentAddress" >@lang('createUserForm.hi.currentAddress') / @lang('createUserForm.en.currentAddress') :</p>
                
							<hr>
                <div class="form-group">
								    <label class="control-label" for="currentAddressOne">@lang('createUserForm.hi.currentAddressOne') / @lang('createUserForm.en.currentAddressOne') :<span class="required">*</span></label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="currentAddressOne" id="currentAddressOne" placeholder="Enter your address">
								</div>

								<div class="form-group">
								    <label class="control-label" for="currentAddressTwo">@lang('createUserForm.hi.currentAddressTwo') / @lang('createUserForm.en.currentAddressTwo') :</label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="currentAddressTwo" id="currentAddressTwo" placeholder="Enter your address">
								</div>

								<div class="form-group">
								    <label class="control-label" for="currentAddressCity">@lang('createUserForm.hi.currentAddressCity') / @lang('createUserForm.en.currentAddressCity') :<span class="required">*</span></label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="currentAddressCity" id="currentAddressCity" placeholder="Enter Address City">
								</div>

								<div class="form-group">
								    <label class="control-label" for="currentAddressPin">@lang('createUserForm.hi.currentAddressPin') / @lang('createUserForm.en.currentAddressPin') :<span class="required">*</span></label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="currentAddressPin" id="currentAddressPin" placeholder="Enter Current Address PIN">
								</div>
            </div>

            <div class="col-md-6">
                <p class="p-a" name="permanentAddress" id="permanentAddress" >@lang('createUserForm.hi.permanentAddress') / @lang('createUserForm.en.permanentAddress') :</p>
							    <div class="checkbox p-a-checkbox">
                    <label>
                      <input type="checkbox" id="sameAsCurrent"> Same as Current Address
                    </label>
                  </div>
							<hr>
								<div class="form-group">
								    <label class="control-label" for="permanentAddressOne">@lang('createUserForm.hi.permanentAddressOne') / @lang('createUserForm.en.permanentAddressOne') :<span class="required">*</span></label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="permanentAddressOne" id="permanentAddressOne" placeholder="Enter your address">
								</div>

								<div class="form-group">
								    <label class="control-label" for="permanentAddressTwo">@lang('createUserForm.hi.permanentAddressTwo') / @lang('createUserForm.en.permanentAddressTwo') :</label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="permanentAddressTwo" id="permanentAddressTwo" placeholder="Enter your address">
								</div>

								<div class="form-group">
								    <label class="control-label" for="permanentAddressCity">@lang('createUserForm.hi.permanentAddressCity') / @lang('createUserForm.en.permanentAddressCity') :<span class="required">*</span></label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style text-capitalize" name="permanentAddressCity" id="permanentAddressCity" placeholder="Enter Address City">
								</div>

								<div class="form-group">
								    <label class="control-label" for="permanentAddressPin">@lang('createUserForm.hi.permanentAddressPin') / @lang('createUserForm.en.permanentAddressPin') :<span class="required">*</span></label>
								      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="permanentAddressPin" id="permanentAddressPin" placeholder="Enter Current Address PIN">
								</div>                         		
                          </div>
                      </div>
                  </div>
                    
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="button" class="btn btn-primary addressDetailsFormSubmit submit-btn-style">Submit</button>
                </div>
              </form> 
            </div>
              												<!-- Address details ends here -->



            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->


      </div>
      <!-- col ends here -->
    </div>
    <!-- Main row ends here -->

    </section>
    <!-- /.Main content -->
  </div>
  <!-- /.content-wrapper -->

  <script type="text/javascript">
  $("#noBasicProfileError").hide();
  var allowBasicFormSubmit = {personalEmail: 1, personalMobileNumber: 1, employeeCode: 1, dateOfBirth: 1};  

  $("#basicDetailsForm").validate({
    rules : {
      "unitIds[]" : {
        required : true
      },
      "employeeCode" : {
        required : true
      },
      "firstName" : {
        required : true,
        maxlength: 20,
        minlength: 1,
        lettersonly: true
      },
      "middleName" : {
        maxlength: 20,
        minlength: 1,
        lettersonly: true
      },
      "lastName" : {
        required: true,
        maxlength: 20,
        minlength: 1,
        lettersonly: true
      },
      "fatherName" : {
        maxlength: 50,
        minlength: 3,
        lettersonly: true
      },
      "motherName" : {
        maxlength: 50,
        minlength: 3,
        lettersonly: true
      },
      "designationId" : {
        required : true
      },
      "qualificationId" : {
        required : true
      },
      "vehicleNumber" : {
        alphanumericWithSpace : true
      },
      "permissionIds[]":{
        required: true
      },
      "dateOfJoining" : {
        required : true
      },
      "gender" : {
        required : true
      },
      "maritalStatus" : {
        required : true
      },
      "spouseName" : {
        maxlength: 50,
        minlength: 3,
        lettersonly: true
      },
      "panNumber" : {
        alphanumeric : true,
        maxlength : 12,
        minlength : 10
      },
      "adhaarNumber" : {
        minlength :12,
        maxlength : 14,
        spacespecial: true

      },
      "officialMobileNumber" : {
        digits: true,
        exactlengthdigits : 10
      },
      "personalMobileNumber" : {
        //required : true,
        digits: true,
        exactlengthdigits : 10
      },
      "officialEmail" : {
        email : true
      },
      "personalEmail" : {
        email : true
      },
      "emergencyContactName" : {
        lettersonly : true,
        maxlength : 50,
        minlength : 3
      },
      "emergencyContactNumber" : {
        digits : true,
        exactlengthdigits : 10
      },
      "relationship" : {
        lettersonly : true,
        maxlength : 50,
        minlength : 3
      },
      "supervisor" : {
        required : true
      },
      "employeeType" : {
         required : true
      },
      "departmentId" : {
        required : true
      },
      "roleId" : {
        required : true
      }
    },
    errorPlacement: function(error, element) {
      if (element.hasClass('select2')) {
        error.insertAfter(element.next('span.select2'));
      } else {
        error.insertAfter(element);
      }
    },
    messages : {
      "unitIds[]" : {
        required : 'Please Select Unit.'
      },
      "employeeCode" : {
        required : 'Please enter employee code.'
      },
      "firstName" : {
        required : 'Please enter employee first name.',
        maxlength: 'Maximum 20 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "middleName" : {
        maxlength: 'Maximum 20 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "lastName" : {
        required : 'Please enter employee last name.',
        maxlength: 'Maximum 20 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "fatherName" : {
        maxlength: 'Maximum 50 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "motherName" : {
        maxlength: 'Maximum 50 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "designationId" : {
        required : 'Please Select designation.'
      },
      "permissionIds[]": {
        required: 'Please select user permissions.'
      },
      "qualificationId" : {
        required : 'Please Select qualification.'
      },
      "dateOfJoining" : {
        required : 'Please Select date of joining.'
      },
      "gender" : {
        required : 'Please Select gender.'
      },
      "maritalStatus" : {
        required : 'Please Select marital status.'
      },
      "spouseName" : {
        maxlength: 'Maximum 50 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "panNumber" : {
        maxlength: 'Maximum 12 characters are allowed.',
        minlength: 'Minimum 10 characters are allowed.'
      },
      "adhaarNumber" : {
        maxlength: 'Maximum 14 characters are allowed.',
        minlength: 'Minimum 12 characters are allowed.'
      },
      "personalMobileNumber" : {
        //required : 'Please enter personal mobile number.'
      },
      "emergencyContactName" : {
        
        maxlength: 'Maximum 50 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "emergencyContactNumber" : {
        digits : 'Please enter only 10 digits numeric values',
        
      },
      "relationship" : {
        maxlength: 'Maximum 50 characters are allowed.',
        minlength: 'Minimum 3 characters are allowed.'
      },
      "supervisor" : {
        required : 'Please Select employee supervisor.'
      },
      "employeeType" : {
        required : 'Please Select employee type.'
      },
      "departmentId" : {
        required : 'Please Select employee department.'
      },
      "roleId" : {
        required : 'Please Select employee Role.'
      }

    }

  });

$("#addressDetailsForm").validate({
  rules : {
    "currentAddressPin" : {
      digits : true,
      exactlengthdigits : 6,
      required : true
    },
    "permanentAddressPin" : {
      digits : true,
      exactlengthdigits : 6,
      required : true
    },
    "currentAddressCity" : {
      lettersonly : true,
      required : true
    },
    "permanentAddressCity" : {
      lettersonly : true,
      required : true
    },
    "currentAddressOne" : {
      required : true
    },
    "permanentAddressOne" : {
      required : true
    }
  },
   messages : {
    "currentAddressPin" : {
      required : 'Please enter valid pin code.'
    },
    "permanentAddressPin" : {
      required : 'Please enter valid pin code.'
    },
    "currentAddressCity" : {
      required : 'Please enter current city name.'
    },
    "permanentAddressCity" : {
      required : 'Please enter permanent city name.'
    },
    "currentAddressOne" : {
      required : 'Please enter current address.'
    },
    "permanentAddressOne" : {
      required : 'Please enter permanent address.'
    }
  }

});

  $.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z," "]+$/i.test(value);
    }, "Please enter only alphabets and spaces.");

  $.validator.addMethod("exactlengthdigits", function(value, element, param) {
       return this.optional(element) || value.length == param;
    }, $.validator.format("Please enter exactly {0} digits."));

  $.validator.addMethod("alphanumericWithSpace", function(value, element) {
    return this.optional(element) || /^[A-Za-z][A-Za-z. \d-]*$/i.test(value);
    }, "Please enter only alphanumeric value.");

  $.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[A-Za-z][A-Za-z.\d]*$/i.test(value);
    }, "Please enter only alphanumeric value.");

  $.validator.addMethod("spacespecial", function(value, element) {
      return this.optional(element) || /^[0-9-,]+(\s{0,1}[0-9-, ])*$/i.test(value); 
    },"Please enter only digits.");

 /* $.validator.addMethod("email", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value);
    }, "Please enter a valid email address.");*/

  var today = new Date();
  var yesterday = moment().subtract(1, 'days')._d;

  //Date picker
  $('#dateOfJoining').datepicker({
    autoclose: true,
    orientation: "bottom" 
  });

  $('#dateOfBirth').datepicker({
    endDate: yesterday,
    autoclose: true,
    orientation: "bottom" 
  });
    
</script>

<script type="text/javascript">
  var tabName = "{{@$data['tabName']}}";
  var lastInsertedEmployee = "{{$lastInsertedEmployee}}";

  $(function(){
    $('.nav-tabs a[href="#tab_'+tabName+'"]').tab('show');

    $('.basicDetailsFormSubmit').on('click', function(){

      //console.log(allowBasicFormSubmit);

      if(allowBasicFormSubmit.employeeCode == 1 && allowBasicFormSubmit.personalEmail == 1 && allowBasicFormSubmit.personalMobileNumber == 1 && allowBasicFormSubmit.dateOfBirth == 1){
        $("#basicDetailsForm").submit();
      }else{
        return false;
      }
    });

    $('.addressDetailsFormSubmit').on('click',function(){
      if(lastInsertedEmployee == 0){
        $("#noBasicProfileError").show().fadeOut(6000);
        return false;
      }else{
        $("#noBasicProfileError").hide();
        $('#addressDetailsForm').submit();
      }
    });
  
  });//end document ready

  $(".checkUnique").on('keyup',function(){
    var employeeCode = $("#employeeCode").val();
    var personalEmail = $("#personalEmail").val();
    var personalMobileNumber = $("#personalMobileNumber").val();

    $.ajax({
      type: 'POST',
      url: '{{route("employees.checkUnique")}}',
      data: {employeeCode: employeeCode, personalEmail: personalEmail, personalMobileNumber: personalMobileNumber},
      success: function(result){

        if(result.employeeCode == 2){
          allowBasicFormSubmit.employeeCode = 0;
          $('.employeeCodeError').text("Employee code already exists.").css('color',"#f00");
        }else{
          $('.employeeCodeError').text("");
          if(result.employeeCode == 0){
            allowBasicFormSubmit.employeeCode = 0;
          }else{
            allowBasicFormSubmit.employeeCode = 1;
          }
        }

        if(result.personalEmail == 2){
          allowBasicFormSubmit.personalEmail = 0;
          $('.personalEmailError').text("Personal email already exists.").css('color',"#f00");
        }else{
          $('.personalEmailError').text("");
          if(result.personalEmail == 0){
            allowBasicFormSubmit.personalEmail = 1;
          }else{
            allowBasicFormSubmit.personalEmail = 1;
          }
        }

        if(result.personalMobileNumber == 2){
          allowBasicFormSubmit.personalMobileNumber = 0;
          $('.personalMobileNumberError').text("Personal mobile number already exists.").css('color',"#f00");
        }else{
          $('.personalMobileNumberError').text("");
          if(result.personalMobileNumber == 0){
            allowBasicFormSubmit.personalMobileNumber = 1;
          }else{
            allowBasicFormSubmit.personalMobileNumber = 1;
          }
        }

      }
    });
  });

  $("#maritalStatus").on('change',function(){
    var maritalStatus = $(this).val();

    if(maritalStatus == 'Married'){
      $(".spouseDiv").show();
    }else{
      $(".spouseDiv").hide();
    }
  });

  $("#dateOfBirth").on('change',function(){
    var dob = $(this).val();
    var doj = $('#dateOfJoining').val();

    if(Date.parse(dob) >= Date.parse(doj)){
      allowBasicFormSubmit.dateOfBirth = 0;
      $(".dateOfBirthErrors").text("Date of birth should be less than joining date.").css('color','#f00');
    }else{
      allowBasicFormSubmit.dateOfBirth = 1;
      $(".dateOfBirthErrors").text("");
    }
  });

  $('#permissionIds').on('change',function(){
    var permissions = $(this).val();
    var units = $('#unitIds').val();

    var va = permissions.indexOf('verify-attendance');
    var rp = permissions.indexOf('reset-password');

    if(units.length > 1){
      if(va != -1){
        $('#permissionIds option[value="verify-attendance"]').prop('selected', false);
        $("#permissionIds").trigger('change.select2');
        alert("You cannot select the permission of verify-attendance or reset-password for a user having multiple units.");
      }

      if(rp != -1){
        $('#permissionIds option[value="reset-password"]').prop('selected', false);
        $("#permissionIds").trigger('change.select2');
        alert("You cannot select the permission of verify-attendance or reset-password for a user having multiple units.");
      }
    }else if(units.length == 1 && (rp != -1 || va != -1)){
      $.ajax({
        type: "POST",
        url: '{{route("employees.checkPermission")}}',
        data: {unit: units[0], reset_password: rp, verify_attendance: va},
        success: function(result){
          var msg = "";
          if(result.reset_password == 0 && result.verify_attendance == 0){
            $('#permissionIds option[value="reset-password"]').prop('selected', false);
            $('#permissionIds option[value="verify-attendance"]').prop('selected', false);
            $("#permissionIds").trigger('change.select2');

            msg = "The selected unit already has a user with reset-password permission (code: "+result.reset_employee_code+") and verify-attendance permission (code: "+result.verify_employee_code+")."
            alert(msg);

          }else if(result.reset_password == 0){
            $('#permissionIds option[value="reset-password"]').prop('selected', false);
            $("#permissionIds").trigger('change.select2');
            
            msg = "The selected unit already has a user with reset-password permission (code: "+result.reset_employee_code+")."
            alert(msg);
          
          }else if(result.verify_attendance == 0){
            $('#permissionIds option[value="verify-attendance"]').prop('selected', false);
            $("#permissionIds").trigger('change.select2');
            
            msg = "The selected unit already has a user with verify-attendance permission (code: "+result.verify_employee_code+")."
            alert(msg);
          }
        }
      });
    }
    
  });

  $("#sameAsCurrent").on('change',function(){
    if($(this).is(':checked')){
      $("#permanentAddressOne").val($('#currentAddressOne').val());
      $("#permanentAddressTwo").val($('#currentAddressTwo').val());
      $("#permanentAddressCity").val($('#currentAddressCity').val());
      $("#permanentAddressPin").val($('#currentAddressPin').val());
    }else{
      $("#permanentAddressOne").val("");
      $("#permanentAddressTwo").val("");
      $("#permanentAddressCity").val("");
      $("#permanentAddressPin").val("");
    }
  });

  // $('#unitIds').on('change',function(){
  //   var unitIds = $(this).val();

  //   $("#permissionIds").val("").trigger('change.select2');

  //   $.ajax({
  //     type: 'POST',
  //     url: "{{route('employees.unitWiseEmployees')}}",
  //     data: {unitIds: unitIds},
  //     success: function(result){
        
  //       $('#supervisor').empty();

  //       if(result.length != 0){
  //         $("#supervisor").append("<option value='' selected disabled>Please select Employee's Supervisor</option>");

  //         $.each(result,function(key,value){
  //           if(value.middle_name){
  //             var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
  //           }else{
  //             var name = value.first_name + ' ' + value.last_name;
  //           }
            
  //           $("#supervisor").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
  //         });
          
  //       }else{
  //         $("#supervisor").append("<option value='' selected disabled>None</option>");
  //       }

  //       $('#otherSupervisor').empty();

  //       if(result.length != 0){
  //         $("#otherSupervisor").append("<option value='' selected disabled>Please select Employee's Other Supervisor</option>");

  //         $.each(result,function(key,value){
  //           if(value.middle_name){
  //             var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
  //           }else{
  //             var name = value.first_name + ' ' + value.last_name;
  //           }
            
  //           $("#otherSupervisor").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
  //         });
          
  //       }else{
  //         $("#otherSupervisor").append("<option value='' selected disabled>None</option>");
  //       }

  //       $('#deputyHodId').empty();

  //       if(result.length != 0){
  //         $("#deputyHodId").append("<option value='' selected disabled>Please select Employee's Dy. HOD</option>");

  //         $.each(result,function(key,value){
  //           if(value.middle_name){
  //             var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
  //           }else{
  //             var name = value.first_name + ' ' + value.last_name;
  //           }
            
  //           $("#deputyHodId").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
  //         });
          
  //       }else{
  //         $("#deputyHodId").append("<option value='' selected disabled>None</option>");
  //       }

  //       $('#hodId').empty();

  //       if(result.length != 0){
  //         $("#hodId").append("<option value='' selected disabled>Please select Employee's HOD</option>");

  //         $.each(result,function(key,value){
  //           if(value.middle_name){
  //             var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
  //           }else{
  //             var name = value.first_name + ' ' + value.last_name;
  //           }
            
  //           $("#hodId").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
  //         });
          
  //       }else{
  //         $("#hodId").append("<option value='' selected disabled>None</option>");
  //       }
  //     }
  //   });
  // });

</script>

@endsection