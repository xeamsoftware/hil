@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">@lang('employeeTransfer.hi.employeeTransferForm') / @lang('employeeTransfer.en.employeeTransferForm')</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-primary">
              @if($errors->transfer->any())
                <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-1">

                  <button type="button" class="close login-alert-close" data-dismiss="alert" aria-hidden="true">×</button>

                    <h4 class="login-error-list">Error</h4>
                    <ul class="login-alert2">

                        @foreach ($errors->transfer->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>  
              @endif

              @if(session()->has('transferSuccess'))
                <div class="alert alert-success alert-dismissible login-alerts-change-pswrd-2">
                  <h4 class="login-error-list">Success</h4>
                    <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('transferSuccess') }}
                </div>
              @endif
            <!-- form start -->
            <form id="employeeTransferForm" method="POST" action="{{route('employees.saveTransferDetails')}}">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label" for="employeeName">@lang('employeeTransfer.hi.employeeName') / @lang('employeeTransfer.en.employeeName')<span class="required">*</span></label>
                            <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="employeeName" id="employeeName" style="width:100%">
                              <option value="" selected disabled>Please Select Employee's Name</option>
                              @if(!$users->isEmpty())
                                @foreach($users as $user)
                                  <option value="{{@$user->id}}">{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}} ({{$user->employee_code}})</option>
                                @endforeach
                              @endif
                          </select>
                      </div>
                      <div class="form-group">
                        <label class="control-label"  for="currentUnit">@lang('employeeTransfer.hi.currentUnit') / @lang('employeeTransfer.en.currentUnit')</label>
                        <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" id="currentUnit" name="currentUnit" placeholder="My current unit name" readonly>
                      </div>

                      <div class="form-group">
                        <label class="control-label"  for="designationName">@lang('employeeTransfer.hi.designationName') / @lang('employeeTransfer.en.designationName')</label>
                        <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" id="designationName" name="designationName" placeholder="This is designation name" readonly>
                      </div>

                      <div class="form-group">
                        <label class="control-label"  for="departmentName">@lang('employeeTransfer.hi.departmentName') / @lang('employeeTransfer.en.departmentName')</label>
                        <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" id="departmentName" name="departmentName" placeholder="This is department name" readonly>
                      </div>

                      <div class="form-group">
                        <label class="control-label"  for="roleName">@lang('employeeTransfer.hi.roleName') / @lang('employeeTransfer.en.roleName')</label>
                        <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" id="roleName" name="roleName" placeholder="This is role name" readonly>
                      </div>

                      <div class="form-group transferUnitNameDiv">
                          <label class="control-label" for="transferUnitName">@lang('employeeTransfer.hi.transferUnitName') / @lang('employeeTransfer.en.transferUnitName')<span class="required">*</span></label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="transferUnitName" id="transferUnitName">
                              <option value="" selected disabled>Please Select Transfer Unit</option>
                              @if(!$data['units']->isEmpty())
                                @foreach($data['units'] as $unit)
                                  <option value="{{@$unit->id}}">{{$unit->name}}</option>
                                @endforeach
                              @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="transferDesignationName">@lang('employeeTransfer.hi.transferDesignationName') / @lang('employeeTransfer.en.transferDesignationName')<span class="required">*</span></label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="transferDesignationName" id="transferDesignationName">
                              <option value="" selected disabled>Please Select Transfer Designation</option>
                              @if(!$data['designations']->isEmpty())
                                @foreach($data['designations'] as $designation)
                                    <option value="{{@$designation->id}}">{{$designation->name}}</option>
                                @endforeach
                              @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="transferDepartmentName">@lang('employeeTransfer.hi.transferDepartmentName') / @lang('employeeTransfer.en.transferDepartmentName')<span class="required">*</span></label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="transferDepartmentName" id="transferDepartmentName">
                              <option value="" selected disabled>Please Select Transfer Department</option>
                              @if(!$data['departments']->isEmpty())
                                @foreach($data['departments'] as $department)
                                    <option value="{{@$department->id}}">{{$department->name}}</option>
                                @endforeach
                              @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="transferRoleName">@lang('employeeTransfer.hi.transferRoleName') / @lang('employeeTransfer.en.transferRoleName')<span class="required">*</span></label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="transferRoleName" id="transferRoleName">
                              <option value="" selected disabled>Please Select Transfer Role</option>
                              @if(!$data['roles']->isEmpty())
                                @foreach($data['roles'] as $role)
                                  @if($role->id != 1)
                                    <option value="{{@$role->id}}">{{$role->name}}</option>
                                  @endif  
                                @endforeach
                              @endif
                          </select>
                      </div>

                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label" for="newSupervisorName">@lang('employeeTransfer.hi.newSupervisorName') / @lang('employeeTransfer.en.newSupervisorName')<span class="required">*</span></label>
                            <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newSupervisorName" id="newSupervisorName"> 
                            <option value="" selected disabled>Please Select Employee</option>
                            @if(!empty($users))
                              @foreach($users as $key => $value)
                                <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                              @endforeach
                            @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="newOtherSupervisorName">@lang('employeeTransfer.hi.newOtherSupervisorName') / @lang('employeeTransfer.en.newOtherSupervisorName')</label>
                            <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newOtherSupervisorName" id="newOtherSupervisorName"> 
                            <option value="" selected disabled>Please Select Employee</option>
                            @if(!empty($users))
                              @foreach($users as $key => $value)
                                <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                              @endforeach
                            @endif
                          </select>
                      </div>
                      
                      <div class="form-group">
                          <label class="control-label" for="newDeputyHodId">@lang('employeeTransfer.hi.newDeputyHodId') / @lang('employeeTransfer.en.newDeputyHodId')</label>
                            <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newDeputyHodId" id="newDeputyHodId">
                            @if(!empty($users))
                              @foreach($users as $key => $value)
                                <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                              @endforeach
                            @endif
                          </select>
                      </div>

                      <input type="hidden" name="userId" id="userId">
                      <input type="hidden" name="oldUnitId" id="oldUnitId">
                      
                      <div class="form-group">
                          <label class="control-label" for="newHodId">@lang('employeeTransfer.hi.newHodId') / @lang('employeeTransfer.en.newHodId')</label>
                            <select class="form-control select2 single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newHodId" id="newHodId">
                            @if(!empty($users))
                              @foreach($users as $key => $value)
                                <option value="{{@$value->id}}">{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}} ({{@$value->employee_code}})</option>
                              @endforeach
                            @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="newDgmId">@lang('employeeTransfer.hi.newDgmId') / @lang('employeeTransfer.en.newDgmId')</label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newDgmId" id="newDgmId">
                              <option value="" selected disabled>Please Select New DGM</option>
                              @if(!$data['dgms']->isEmpty())
                                @foreach($data['dgms'] as $key => $dgm)
                                  <option value="{{$dgm->id}}">{{$dgm->first_name}} {{@$dgm->middle_name}} {{@$dgm->last_name}} ({{@$dgm->employee_code}})</option>
                                @endforeach
                              @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="newGmId">@lang('employeeTransfer.hi.newGmId') / @lang('employeeTransfer.en.newGmId')</label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newGmId" id="newGmId">
                              <option value="" selected disabled>Please Select New GM</option>
                             @if(!$data['gms']->isEmpty())
                              @foreach($data['gms'] as $key => $gm)
                                <option value="{{$gm->id}}">{{$gm->first_name}} {{@$gm->middle_name}} {{@$gm->last_name}} ({{@$gm->employee_code}})</option>
                              @endforeach
                             @endif
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="control-label" for="newCmdId">@lang('employeeTransfer.hi.newCmdId') / @lang('employeeTransfer.en.newCmdId')</label>
                            <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="newCmdId" id="newCmdId">
                              <option value="" selected disabled>Please Select New CMD</option>
                              @if(!$data['cmds']->isEmpty())
                                @foreach($data['cmds'] as $key => $cmd)
                                  <option value="{{$cmd->id}}">{{$cmd->first_name}} {{@$cmd->middle_name}} {{@$cmd->last_name}} ({{@$cmd->employee_code}})</option>
                                @endforeach
                              @endif
                          </select>
                      </div>
                  </div>
              </div>
              <!-- row ends here -->
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">@lang('employeeTransfer.hi.submit') / @lang('employeeTransfer.en.submit')</button>
                <a href="{{route('employees.dashboard')}}" class="btn btn-default">@lang('employeeTransfer.hi.cancel') / @lang('employeeTransfer.en.cancel')</a>
              </div>
            </form>
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $("#employeeTransferForm").validate({
      rules : {
        "employeeName" : {
          required : true
        },
        "transferUnitName" : {
          required : true
        },
        "transferDesignationName" : {
          required : true
        },
        "newSupervisorName" : {
          required : true
        },
        "newDeputyHodId" : {
          //required : true
        },
        "newHodId" : {
          //required : true
        },
        "newDgmId" : {
          //required : true
        },
        "newGmId" : {
          //required : true
        },
        "newCmdId" : {
          //required : true
        },
        "transferDepartmentName" : {
          required : true
        },
        "transferRoleName" : {
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
        "transferUnitName" : {
          required : 'Please select new unit name.',
        },
        "newCmdId" : {
          //required : 'Please select new CMD name.',
        },
        "newGmId" : {
          //required : 'Please select new GM name.',
        },
        "newDgmId" : {
          //required : 'Please select new DGM name.',
        },
        "newHodId" : {
          //required : 'Please select new HOD name.',
        },
        "newDeputyHodId" : {
          //required : 'Please select new Deputy HOD name.',
        },
        "newSupervisorName" : {
          required : 'Please select new Supervisor name.',
        },
        "transferDesignationName" : {
          required : 'Please select new Designation name.',
        },
        "employeeName" : {
          required : 'Please select Employee Name.',
        },
        "transferDepartmentName" : {
          required : 'Please select Transfer Department Name.',
        },
        "transferRoleName" : {
          required : 'Please select Transfer Role Name.',
        }
      }
    });
  </script>

  <script type="text/javascript">
    $(".transferUnitNameDiv").hide();
    $("#employeeName").on('change',function(){
      var userId = $(this).val();

      $.ajax({
        type: 'POST',
        url: "{{route('employees.userUnitAndDesignation')}}",
        data: {userId: userId},
        success: function(result){
          if(result.unitName){
            $("#currentUnit").val(result.unitName);
          }

          if(result.role){
            $("#roleName").val(result.role);
          }

          if(result.department){
            $("#departmentName").val(result.department);
          }

          if(result.designation){
            $("#designationName").val(result.designation);
          }

          if(result.unitId){
            $("#transferUnitName option[value='"+result.unitId+"']").prop('disabled',true);
          }

          $("#transferUnitName option[value!='"+result.unitId+"']").prop('disabled',false);
          $(".transferUnitNameDiv").show();

          $("#userId").val(userId);
          $("#oldUnitId").val(result.unitId);
        }
      });
    });

    $('#transferUnitName').on('change',function(){
    var unitId = $(this).val();

    unitIds = [];
    unitIds.push(unitId);

    // $.ajax({
    //   type: 'POST',
    //   url: "{{route('employees.unitWiseEmployees')}}",
    //   data: {unitIds: unitIds},
    //   success: function(result){
        
    //     $('#newSupervisorName').empty();

    //     if(result.length != 0){
    //       $("#newSupervisorName").append("<option value='' selected disabled>Please select Employee's Supervisor</option>");

    //       $.each(result,function(key,value){
    //         if(value.middle_name){
    //           var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
    //         }else{
    //           var name = value.first_name + ' ' + value.last_name;
    //         }
            
    //         $("#newSupervisorName").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
    //       });
          
    //     }else{
    //       $("#newSupervisorName").append("<option value='' selected disabled>None</option>");
    //     }

    //     $('#newOtherSupervisorName').empty();

    //     if(result.length != 0){
    //       $("#newOtherSupervisorName").append("<option value='' selected disabled>Please select Employee's Other Supervisor</option>");

    //       $.each(result,function(key,value){
    //         if(value.middle_name){
    //           var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
    //         }else{
    //           var name = value.first_name + ' ' + value.last_name;
    //         }
            
    //         $("#newOtherSupervisorName").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
    //       });
          
    //     }else{
    //       $("#newOtherSupervisorName").append("<option value='' selected disabled>None</option>");
    //     }

    //     $('#newDeputyHodId').empty();

    //     if(result.length != 0){
    //       $("#newDeputyHodId").append("<option value='' selected disabled>Please select Employee's Dy. HOD</option>");

    //       $.each(result,function(key,value){
    //         if(value.middle_name){
    //           var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
    //         }else{
    //           var name = value.first_name + ' ' + value.last_name;
    //         }
            
    //         $("#newDeputyHodId").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
    //       });
          
    //     }else{
    //       $("#newDeputyHodId").append("<option value='' selected disabled>None</option>");
    //     }

    //     $('#newHodId').empty();

    //     if(result.length != 0){
    //       $("#newHodId").append("<option value='' selected disabled>Please select Employee's HOD</option>");

    //       $.each(result,function(key,value){
    //         if(value.middle_name){
    //           var name = value.first_name + ' ' + value.middle_name + ' ' + value.last_name;
    //         }else{
    //           var name = value.first_name + ' ' + value.last_name;
    //         }
            
    //         $("#newHodId").append('<option value="'+value.id+'">'+name+' ('+value.employee_code+')</option>');
    //       });
          
    //     }else{
    //       $("#newHodId").append("<option value='' selected disabled>None</option>");
    //     }
    //   }
    // });
  });
  </script>

  @endsection