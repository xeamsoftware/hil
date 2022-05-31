@extends('admins.layouts.app')







@section('content')



<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper content-aside">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>@lang('leaveAccumulationForm.hi.leaveAccumulationForm') / @lang('leaveAccumulationForm.en.leaveAccumulationForm')</h1>

      <ol class="breadcrumb">

        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <!-- Small boxes (Stat box) -->

        <div class="row">

          <div class="col-md-12">

              <div class="box box-primary lt-limit-box">

                  @if(session()->has('accumulationError'))

                    <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-2">

                      <h4 class="login-error-list">Error</h4>

                        <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>

                        {{ session()->get('accumulationError') }}

                    </div>

                  @endif

              <!-- /.box-header -->

              <!-- form start -->

              <form id="leaveAccumulationsForm" action="{{route('leaves.saveEmployeeAccumulation')}}" class="form-side" method="POST">

                {{ csrf_field() }}

                <!-- Employee ID and Name Section starts here -->

                  <div class="row">

                      <div class="col-md-4 col-md-offset-2">

                          <div class="input-group margin">

                            <div class="input-group-btn">

                              <button type="button" class="btn btn-warning">@lang('leaveAccumulationForm.hi.employeeCode') / @lang('leaveAccumulationForm.en.employeeCode')</button>

                            </div>

                            <!-- /btn-group -->

                            <input type="text" class="form-control" placeholder="Employee code here" name="employeeCode" id="employeeCode">

                          </div>

                      </div>

                      <div class="col-md-4">

                          <div class="input-group margin">

                            <div class="input-group-btn">

                              <button type="button" class="btn btn-warning">@lang('leaveAccumulationForm.hi.employeeName') / @lang('leaveAccumulationForm.en.employeeName')</button>

                            </div>

                            <!-- /btn-group -->

                            <input type="text" class="form-control" placeholder="Employee name here" name="employeeName" id="employeeName" readonly>

                          </div>

                      </div>

                  </div>



                <!-- Employee ID and Name Section ends here -->



                <table class="table table-striped">

                  <thead>

                    <tr>

                        <th class="ltl-heading1">@lang('leaveAccumulationForm.hi.leaveType') / @lang('leaveAccumulationForm.en.leaveType')</th>

                          <th class="ltl-heading2">@lang('leaveAccumulationForm.hi.maxYearlyLimit') / @lang('leaveAccumulationForm.en.maxYearlyLimit')</th>

                          <th class="ltl-heading3">@lang('leaveAccumulationForm.hi.totalRemainingCount') / @lang('leaveAccumulationForm.en.totalRemainingCount')</th>

                      </tr>

                  </thead>

                  <tbody>

                  <tr>

                      <td>@lang('leaveAccumulationForm.hi.casualLeave') / @lang('leaveAccumulationForm.en.casualLeave')</td>

                      <td><input type="text" class="form-control ltl-input-field" id="cLMaxYearlyLimit" placeholder="12" name="cLMaxYearlyLimit"></td>

                      <td><input type="text" class="form-control ltl-input-field" id="cLTotalRemainingCount" placeholder="12" name="cLTotalRemainingCount"></td>

                  </tr>

                  <tr>

                      <td>@lang('leaveAccumulationForm.hi.halfPaySickLeave') / @lang('leaveAccumulationForm.en.halfPaySickLeave')</td>

                      <td><input type="text" class="form-control ltl-input-field" id="hpslMaxYearlyLimit" placeholder="12" name="hpslMaxYearlyLimit"></td>

                      <td><input type="text" class="form-control ltl-input-field" id="hpslTotalRemainingCount" placeholder="12" name="hpslTotalRemainingCount"></td>

                  </tr>

                  <tr>

                      <td>@lang('leaveAccumulationForm.hi.nonEncashable') / @lang('leaveAccumulationForm.en.nonEncashable')</td>

                      <td><input type="text" class="form-control ltl-input-field" id="eLNonCashableMaxYearlyLimit" placeholder="12" name="eLNonCashableMaxYearlyLimit"></td>

                      <td><input type="text" class="form-control ltl-input-field" id="eLNonCashableTotalRemainingCount" placeholder="12" name="eLNonCashableTotalRemainingCount"></td>

                  </tr>

                  <tr>

                    <td>@lang('leaveAccumulationForm.hi.encashable') / @lang('leaveAccumulationForm.en.encashable')</td>

                      <td><input type="text" class="form-control ltl-input-field" id="eLCashableMaxYearlyLimit" placeholder="12" name="eLCashableMaxYearlyLimit"></td>

                      <td><input type="text" class="form-control ltl-input-field" id="eLCashableTotalRemainingCount" placeholder="12" name="eLCashableTotalRemainingCount"></td>

                      <span id="elErrors"></span>

                  </tr>

                  <tr>

                    <td>@lang('leaveAccumulationForm.hi.shortLeave') / @lang('leaveAccumulationForm.en.shortLeave')</td>

                      <td><input type="text" class="form-control ltl-input-field" id="shortLeaveMaxYearlyLimit" placeholder="12" name="shortLeaveMaxYearlyLimit" value="NA" readonly></td>

                      <td><input type="text" class="form-control ltl-input-field" id="shortLeaveTotalRemainingCount" placeholder="12" name="shortLeaveTotalRemainingCount"></td>

                  </tr>

                  <tr>

                    <td>@lang('leaveAccumulationForm.hi.restrictedHoliday') / @lang('leaveAccumulationForm.en.restrictedHoliday')</td>

                      <td><input type="text" class="form-control ltl-input-field" id="rhMaxYearlyLimit" placeholder="2" name="rhMaxYearlyLimit"></td>

                      <td><input type="text" class="form-control ltl-input-field" id="rhTotalRemainingCount" placeholder="2" name="rhTotalRemainingCount"></td>

                  </tr>

                  </tbody>

                </table>

                <div class="box-footer footer-btn-center">

                  <button type="button" class="btn btn-primary submit-btn-style leaveAccumulationsFormSubmit">@lang('leaveAccumulationForm.hi.formSubmit') / @lang('leaveAccumulationForm.en.formSubmit')</button>

                  <a href="{{route('employees.dashboard')}}" class="cancel-btn-styling">@lang('leaveAccumulationForm.hi.cancel') / @lang('leaveAccumulationForm.en.cancel')</a>

                </div>

              </form>

                </div>

                <!-- /.box-body -->

            </div>

          </div>

      <!-- /.row -->

      <!-- Main row -->

    </section>

    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->

  <script type="text/javascript">

    //jquery validations

    $(document).ready(function(){

      $("#leaveAccumulationsForm").validate({

        rules : {

          "cLMaxYearlyLimit" : {

            required : true,

            dotspecial : true

          },

          "hpslMaxYearlyLimit" : {

            required : true,

            dotspecial : true

          },

          "eLNonCashableMaxYearlyLimit" : {

            required : true,
            dotspecial : true

          },

          "eLCashableMaxYearlyLimit" : {

            required : true,
            dotspecial : true

          },

          "rhMaxYearlyLimit" : {

            required : true,

            dotspecial : true

          },

          "cLTotalRemainingCount" : {

            required : true,

            dotspecial : true

          },

          "hpslTotalRemainingCount" : {

            required : true,

            dotspecial : true

          },

          "eLNonCashableTotalRemainingCount" : {

            required : true,

            dotspecial : true

          },

          "eLCashableTotalRemainingCount" : {

            required : true,

            dotspecial : true

          },

          "rhTotalRemainingCount" : {

            required : true,

            dotspecial : true

          }

        },

        messages : {

          "cLMaxYearlyLimit" : {

            required : 'Please enter employee max yearly leave limit.'

          },

          "hpslMaxYearlyLimit" : {

            required : 'Please enter employee max yearly leave limit.'

          },

          "eLNonCashableMaxYearlyLimit" : {

            required : 'Please enter employee max yearly leave limit.'

          },

          "eLCashableMaxYearlyLimit" : {

            required : 'Please enter employee max yearly leave limit.'

          },

          "rhMaxYearlyLimit" : {

            required : 'Please enter employee max yearly leave limit.'

          },

          "cLTotalRemainingCount" : {

            required : 'Please enter employee Total Remaining Count.'

          },

          "hpslTotalRemainingCount" : {

            required : 'Please enter employee Total Remaining Count.'

          },

          "eLNonCashableTotalRemainingCount" : {

            required : 'Please enter employee Total Remaining Count.'

          },

          "eLCashableTotalRemainingCount" : {

            required : 'Please enter employee Total Remaining Count.'

          },

          "rhTotalRemainingCount" : {

            required : 'Please enter employee Total Remaining Count.'

          }

        }

      });

    });



    $.validator.addMethod("dotspecial", function(value, element) {

      return this.optional(element) || /^[0-9-.,]+[.0-9-,]*$/i.test(value);

    },"Please enter only digits and dot(.).");



  </script>



  <script type="text/javascript">

    var allowFormSubmit = {el: 1};



  	$(".leaveAccumulationsFormSubmit").on('click',function(){

      var sum = Number($("#eLNonCashableTotalRemainingCount").val()) + Number($("#eLCashableTotalRemainingCount").val());

      var eLCashableMaxYearlyLimit = Number($("#eLCashableMaxYearlyLimit").val());
      if(eLCashableMaxYearlyLimit > 30){
        allowFormSubmit.el = 0;

        $("#elErrors").text("Max yearly limit cannot be more than 30 in EL encashment").css("color","#f00");
      }
      else if(!Number.isNaN(sum)){

        if(sum > 300){

          allowFormSubmit.el = 0;

          $("#elErrors").text("Sum of both EL leaves should not be more than 300").css("color","#f00");

        }else{

          allowFormSubmit.el = 1;

          $("#elErrors").text("");

        }

      }else{

        allowFormSubmit.el = 0;

        $("#elErrors").text("Please fill proper values.").css("color","#f00");

      }



      if(allowFormSubmit.el == 0){

        return false;

      }else{

        $("#leaveAccumulationsForm").submit();

      }

    });



    $("#employeeCode").on('keyup',function(){

      var employeeCode = $(this).val();



      $.ajax({

        type: "POST",

        url: "{{ route('leaves.employeeLeaveAccumulations') }}",

        data: {employeeCode: employeeCode},

        success: function(result){



          if(result.status){

            $("#employeeName").val(result.employeeName);



            $("#cLMaxYearlyLimit").val(result.cLMaxYearlyLimit);

            $("#cLTotalRemainingCount").val(result.cLTotalRemainingCount);



            $("#hpslMaxYearlyLimit").val(result.hpslMaxYearlyLimit);

            $("#hpslTotalRemainingCount").val(result.hpslTotalRemainingCount);



            $("#eLNonCashableMaxYearlyLimit").val(result.eLNonCashableMaxYearlyLimit);

            $("#eLNonCashableTotalRemainingCount").val(result.eLNonCashableTotalRemainingCount);



            $("#eLCashableMaxYearlyLimit").val(result.eLCashableMaxYearlyLimit);

            $("#eLCashableTotalRemainingCount").val(result.eLCashableTotalRemainingCount);



            $("#shortLeaveMaxYearlyLimit").val(result.shortLeaveMaxYearlyLimit);

            $("#shortLeaveTotalRemainingCount").val(result.shortLeaveTotalRemainingCount);



            $("#rhMaxYearlyLimit").val(result.rhMaxYearlyLimit);

            $("#rhTotalRemainingCount").val(result.rhTotalRemainingCount);

          }

        }

      });

    });

  </script>



  @endsection
