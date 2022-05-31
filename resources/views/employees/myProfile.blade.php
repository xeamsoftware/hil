@extends('admins.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Profile
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{$user->profile_pic}}" alt="User profile picture">

              <h3 class="profile-username text-center">{{@$user->first_name}} {{@$user->middle_name}} {{@$user->last_name}}</h3>

              <p class="text-muted text-center">{{@$role}}</p>
              <a href="{{route('employees.changePassword')}}" class="btn btn-primary btn-block"><b>Change Password</b></a>
              <a href="javascript:void(0)" class="btn btn-primary btn-block changeProfilePicture"><b>Upload Profile Picture</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Qualification</strong>

              <p class="text-muted">
                <span class="label label-warning">{{@$user->userQualification->qualification->name}}</span>
              </p>

              <hr>

              <!--<strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

              <p>
                <span class="label label-danger">UI Design</span>
                <span class="label label-success">Coding</span>
                <span class="label label-info">Javascript</span>
                <span class="label label-warning">PHP</span>
                <span class="label label-primary">Node.js</span>
              </p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs edit-nav-styling">
              <li class="active"><a href="#basicTab" data-toggle="tab">@lang('createUserForm.hi.basicDetail') / @lang('createUserForm.en.basicDetail')</a></li>
              <li><a href="#addressTab" data-toggle="tab">@lang('createUserForm.hi.addressDetail') / @lang('createUserForm.en.addressDetail')</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="basicTab">
                <table class="table table-striped table-bordered">
                  <tr>
                    <th style="width: 50%">Field</th>
                    <th style="width: 50%">Value</th>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.unitId') / @lang('createUserForm.en.unitId')</em></td>
                    <td>
                      @foreach($units as $unit)
                        <span class="label label-success">{{@$unit->unit->name}}</span>
                      @endforeach
                    </td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.employeeCode') / @lang('createUserForm.en.employeeCode')</em></td>
                    <td>{{@$user->employee_code}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.employeeType') / @lang('createUserForm.en.employeeType')</em></td>
                    <td>{{@$user->employee_type}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.fatherName') / @lang('createUserForm.en.fatherName')</em></td>
                    <td>{{@$user->userProfile->father_name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.motherName') / @lang('createUserForm.en.motherName')</em></td>
                    <td>{{@$user->userProfile->mother_name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.designationId') / @lang('createUserForm.en.designationId')</em></td>
                    <td>{{@$user->userProfile->designation->name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.departmentId') / @lang('createUserForm.en.departmentId')</em></td>
                    <td>{{@$user->userProfile->department->name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.qualificationId') / @lang('createUserForm.en.qualificationId')</em></td>
                    <td>{{@$user->userQualification->qualification->name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.dateOfJoining') / @lang('createUserForm.en.dateOfJoining')</em></td>
                    <td>{{date("d/m/Y",strtotime(@$user->userProfile->joining_date))}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.dateOfBirth') / @lang('createUserForm.en.dateOfBirth')</em></td>
                    <td>@if(!empty($user->userProfile->birth_date)){{date("d/m/Y",strtotime(@$user->userProfile->birth_date))}}@endif</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.gender') / @lang('createUserForm.en.gender')</em></td>
                    <td>{{@$user->userProfile->gender}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.permissionIds') / @lang('createUserForm.en.permissionIds')</em></td>
                    <td>
                    @foreach($permissions as $permission)  
                      <span class="label label-primary">{{$permission}}</span>
                    @endforeach  
                    </td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.vehicleNumber') / @lang('createUserForm.en.vehicleNumber')</em></td>
                    <td>{{@$user->userProfile->vehicle_number}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.maritalStatus') / @lang('createUserForm.en.maritalStatus')</em></td>
                    <td>{{@$user->userProfile->marital_status}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.spouseName') / @lang('createUserForm.en.spouseName')</em></td>
                    <td>{{@$user->userProfile->spouse_name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.panNumber') / @lang('createUserForm.en.panNumber')</em></td>
                    <td>{{@$user->userProfile->pan_number}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.adhaarNumber') / @lang('createUserForm.en.adhaarNumber')</em></td>
                    <td>{{@$user->userProfile->adhaar_number}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.mobileNumberOfficial') / @lang('createUserForm.en.mobileNumberOfficial')</em></td>
                    <td>{{@$user->official_mobile_number}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.mobileNumberPersonal') / @lang('createUserForm.en.mobileNumberPersonal')</em></td>
                    <td>{{@$user->personal_mobile_number}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.emailOfficial') / @lang('createUserForm.en.emailOfficial')</em></td>
                    <td>{{@$user->official_email}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.emailPersonal') / @lang('createUserForm.en.emailPersonal')</em></td>
                    <td>{{@$user->personal_email}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.emergencyContactName') / @lang('createUserForm.en.emergencyContactName')</em></td>
                    <td>{{@$user->userProfile->emergency_contact_name}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.contactNoEmergency') / @lang('createUserForm.en.contactNoEmergency')</em></td>
                    <td>{{@$user->userProfile->emergency_contact_number}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.relationshipId') / @lang('createUserForm.en.relationshipId')</em></td>
                    <td>{{@$user->userProfile->relationship}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.bloodGroupId') / @lang('createUserForm.en.bloodGroupId')</em></td>
                    <td>{{@$user->userProfile->blood_group}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.supervisor') / @lang('createUserForm.en.supervisor')</em></td>
                    <td>
                    @if(!empty($user->userSupervisor))  
                      {{@$user->userSupervisor->supervisor->first_name}} {{@$user->userSupervisor->supervisor->middle_name}} {{@$user->userSupervisor->supervisor->last_name}}
                      ({{@$user->userSupervisor->supervisor->employee_code}})
                    @else
                      {{'None'}}
                    @endif
                    </td>
                  </tr>

                  @if(!empty($user->otherSupervisor))
                  <tr>
                    <td><em>@lang('createUserForm.hi.otherSupervisor') / @lang('createUserForm.en.otherSupervisor')</em></td>
                    <td>
                      {{@$user->otherSupervisor->supervisor->first_name}} {{@$user->otherSupervisor->supervisor->middle_name}} {{@$user->otherSupervisor->supervisor->last_name}}
                      ({{@$user->otherSupervisor->supervisor->employee_code}})
                    </td>
                  </tr>
                  @endif

                  @if(!$leaveApprovalAuthorities->isEmpty())
                    @foreach($leaveApprovalAuthorities as $leaveApprovalAuthority)
                      <tr>
                        <td>
                          <em>
                          @if($leaveApprovalAuthority->priority == '2')  
                            @lang('createUserForm.hi.dyHod') / @lang('createUserForm.en.dyHod')

                          @elseif($leaveApprovalAuthority->priority == '3')  
                            @lang('createUserForm.hi.hod') / @lang('createUserForm.en.hod')

                          @elseif($leaveApprovalAuthority->priority == '4')  
                            @lang('createUserForm.hi.dgm') / @lang('createUserForm.en.dgm') 

                          @elseif($leaveApprovalAuthority->priority == '5')  
                            @lang('createUserForm.hi.gm') / @lang('createUserForm.en.gm') 

                          @elseif($leaveApprovalAuthority->priority == '6')  
                            @lang('createUserForm.hi.cmd') / @lang('createUserForm.en.cmd')
                            
                          @endif      
                          </em>
                        </td>
                        <td>
                          {{$leaveApprovalAuthority->supervisor->first_name}} {{@$leaveApprovalAuthority->supervisor->middle_name}} {{@$leaveApprovalAuthority->supervisor->last_name}}
                          ({{@$leaveApprovalAuthority->supervisor->employee_code}})
                        </td>
                      </tr>
                    @endforeach  
                  @endif
                  
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="addressTab">
                <!-- The timeline -->
                <table class="table table-striped table-bordered">
                  <tr>
                    <th style="width: 50%">@lang('createUserForm.hi.currentAddress') / @lang('createUserForm.en.currentAddress') :</th>
                    <th style="width: 50%"></th>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.currentAddressOne') / @lang('createUserForm.en.currentAddressOne')</em></td>
                    <td>{{@$user->userAddress->current_address1}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.currentAddressTwo') / @lang('createUserForm.en.currentAddressTwo')</em></td>
                    <td>{{@$user->userAddress->current_address2}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.currentAddressCity') / @lang('createUserForm.en.currentAddressCity')</em></td>
                    <td>{{@$user->userAddress->current_address_city}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.currentAddressPin') / @lang('createUserForm.en.currentAddressPin')</em></td>
                    <td>{{@$user->userAddress->current_address_pin}}</td>
                  </tr>
                </table>
                <table class="table table-striped table-bordered">
                  <tr>
                    <th style="width: 50%">@lang('createUserForm.hi.permanentAddress') / @lang('createUserForm.en.permanentAddress') :</th>
                    <th style="width: 50%"></th>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.permanentAddressOne') / @lang('createUserForm.en.permanentAddressOne')</em></td>
                    <td>{{@$user->userAddress->permanent_address1}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.permanentAddressTwo') / @lang('createUserForm.en.permanentAddressTwo')</em></td>
                    <td>{{@$user->userAddress->permanent_address2}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.permanentAddressCity') / @lang('createUserForm.en.permanentAddressCity')</em></td>
                    <td>{{@$user->userAddress->permanent_address_city}}</td>
                  </tr>
                  <tr>
                    <td><em>@lang('createUserForm.hi.permanentAddressPin') / @lang('createUserForm.en.permanentAddressPin')</em></td>
                    <td>{{@$user->userAddress->permanent_address_pin}}</td>
                  </tr>
                </table>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        
      </div>
      <!-- /.row -->
      <!-- Main row -->
      

    </section>
    <!-- /.content -->

    <div class="modal fade" id="changeProfilePictureModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Change Profile Picture</h4>
            </div>
            <div class="modal-body">
              <form id="profilePictureForm" action="{{route('employees.changeProfilePicture')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="box-body">
                    
                    <div class="form-group">
                      <label for="profilePic" class="">Select Picture</label>
                      <input type="file" class="form-control" id="profilePic" name="profilePic">
                    </div>
                                 
                  </div>
                  <!-- /.box-body -->
                  <br>

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="profilePictureFormSubmit">Submit</button>
                  </div>
            </form>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
      </div>
        <!-- /.modal -->

  </div>
  <!-- /.content-wrapper -->

  <script>
    $(".changeProfilePicture").on('click',function(){
        $("#changeProfilePictureModal").modal('show');
    });

    $("#profilePictureForm").validate({
      rules :{
          "profilePic" : {
              required: true,
              accept: "image/*",
              filesize: 1048576    //1 MB
          }
      },
      messages :{
          "profilePic" : {
              required : 'Please select an image.',
              accept : 'Please select a valid image format.',
              filesize: 'Filesize should be less than 1 MB.'
          }
      }
    });

    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param) 
    });

  </script>

  @endsection