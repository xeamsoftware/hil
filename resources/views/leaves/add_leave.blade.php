@extends('admins.layouts.app')

@section('content')

    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="text-center">@lang('applyLeaveForm.hi.applyLeaveForm') / @lang('applyLeaveForm.en.applyLeaveForm')</h1>
            <ol class="breadcrumb">

                <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            </ol>
        </section>

    {{--        <div class="row al-panelbox">--}}
    {{--            <div class="col-md-3">--}}
    {{--                <div class="panel panel-success leaves-panel-below">--}}
    {{--                    <div class="panel-heading text-center leaves-panel-sec">Total Remaining Leaves:--}}
    {{--                        <span class="label label-success" id="totalLeavesVal">0</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-md-3">--}}
    {{--                <div class="panel panel-danger">--}}
    {{--                    <div class="panel-heading text-center leaves-panel-sec">Yearly Balance Leaves:--}}
    {{--                        <span class="label label-danger" id="yearlyBalanceLeavesVal">0</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-md-3">--}}
    {{--                <div class="panel panel-primary">--}}
    {{--                    <div class="panel-heading text-center leaves-panel-sec">Processing Leave:--}}
    {{--                        <span class="label label-success" id="processingLeavesVal">0</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-md-3">--}}
    {{--                <div class="panel panel-info">--}}
    {{--                    <div class="panel-heading text-center leaves-panel-sec"><span class="yearlyLeavesTaken">Yearly Leaves Taken:</span>--}}
    {{--                        <span class="label label-primary" id="yearlyLeavesTakenVal">0</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    <!-- Main content -->
        <section class="content al-content-body">
            <!-- Small boxes (Stat box) -->
            <div class="row">

                <div class="col-md-12">
                    <div class="box box-primary">
                        <form id="applyLeaveForm" action="{{route('leaves.saveLeave')}}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-1">

                                        <button type="button" class="close login-alert-close" data-dismiss="alert" aria-hidden="true">×</button>

                                        <h4 class="login-error-list">Error</h4>
                                        <ul class="login-alert2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                @elseif(session()->has('leaveError'))
                                    <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-2">
                                        <h4 class="login-error-list">Error</h4>
                                        <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                                        {{ session()->get('leaveError') }}
                                    </div>


                                @elseif(session()->has('leaveSuccess'))
                                    <div class="alert alert-success alert-dismissible login-alerts-change-pswrd-2">
                                        <h4 class="login-success-list">Success</h4>
                                        <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                                        {{ session()->get('leaveSuccess') }}
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_id">Employee</label>
                                            <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="user_id" id="user_id">
                                                <option value="" selected disabled>Please select employees</option>
                                                @foreach($data['employees'] as $employee)
                                                    <option value="{{ $employee->id }}">{{ @$employee->first_name }}{{ @$employee->last_name }} ({{ @$employee->employee_code }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="leaveTypeId">@lang('applyLeaveForm.hi.leaveType') / @lang('applyLeaveForm.en.leaveType')</label>
                                            <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="leaveTypeId" id="leaveTypeId">
                                                <option value="" selected disabled>Please select leave type</option>
                                                @if(!$data['leaveTypes']->isEmpty())
                                                    @foreach($data['leaveTypes'] as $leaveType)
                                                        @if(!in_array($leaveType->id, [7]))
                                                            <option value="{{$leaveType->id}}">{{$leaveType->hindi_name}} / {{$leaveType->name}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group halfLeaveDiv apply-leave-halfday">
                                    <label for="halfLeave" class="half-day-leave">@lang('applyLeaveForm.hi.halfLeave') / @lang('applyLeaveForm.en.halfLeave')</label>

                                    <input type="checkbox" name="halfLeave" id="halfLeave" value="0">
                                </div>

                                <div class="firstSecondHalfDiv">
                                    <input type="radio" name="firstSecondHalf" value="First Half" checked><span class="half-day-leave">First Half</span><br>
                                    <input type="radio" name="firstSecondHalf" value="Second Half"><span class="half-day-leave">Second Half</span><br>
                                </div>

                                <div class="hpslFullHalfPayDiv">
                                    <input type="radio" name="fullHalfPay" value="Half-Pay" class="fullHalfPay" checked><span class="hpslFullHalfPayStatus">Half-Pay</span><br>
                                    <input type="radio" name="fullHalfPay" value="Full-Pay" class="fullHalfPay"><span class="hpslFullHalfPayStatus">Full-Pay</span><br>
                                </div>

                                <div class="encashmentDiv">
                                    <input type="radio" id="encash_takeleave" name="encashmentStatus" value="0" checked><span class="encashment-status">Take Leave</span><br>
                                    <input type="radio" id="encash_Leave" name="encashmentStatus" value="1"><span clasid="classshment-status" >Encash Leave</span><br>
                                </div>

{{--                                @if($user->employee_type == 'Workman')--}}
                                    <div class="row" id="encash_weekoff" >
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fromDate">@lang('applyLeaveForm.hi.selectWeekoff') / @lang('applyLeaveForm.en.selectWeekoff')</label>
                                                <div class="input-group date single-input-lbl">
                                                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right date-input input-sm basic-detail-input-style al-date-input" name="weekoff" id="weekoff" placeholder="01/04/2021" readonly>
                                                </div>
                                                <span class="weekoffErrors"></span>
                                            </div>
                                        </div>
                                    </div>
{{--                                @endif--}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fromDate">@lang('applyLeaveForm.hi.fromDate') / @lang('applyLeaveForm.en.fromDate')</label>
                                            <div class="input-group date single-input-lbl">
                                                <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control selectDate pull-right date-input input-sm basic-detail-input-style al-date-input" name="fromDate" id="fromDate" placeholder="28/11/2017" readonly>
                                            </div>
                                            <span class="dateErrors"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="encash_toDate">
                                        <div class="form-group">
                                            <label for="toDate">
                                                @lang('applyLeaveForm.hi.toDate') / @lang('applyLeaveForm.en.toDate')
                                            </label>
                                            <div class="input-group date single-input-lbl">
                                                <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control selectDate pull-right date-input input-sm basic-detail-input-style al-date-input" name="toDate" id="toDate" placeholder="28/11/2017" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="fromToTimeDiv">
                                    <div class="col-md-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <label for="fromTime">@lang('applyLeaveForm.hi.fromTime') / @lang('applyLeaveForm.en.fromTime')</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>

                                                    <input type="text" class="form-control selectTime input-sm basic-detail-input-style" placeholder="12:00 PM" name="fromTime" id="fromTime" readonly>
                                                </div>
                                                <span class="timeErrors"></span>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <label for="toTime">@lang('applyLeaveForm.hi.toTime') / @lang('applyLeaveForm.en.toTime')</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>

                                                    <input type="text" class="form-control selectTime input-sm basic-detail-input-style" placeholder="12:00 PM" name="toTime" id="toTime" readonly>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="numberOfDays">@lang('applyLeaveForm.hi.numberOfDays') / @lang('applyLeaveForm.en.numberOfDays') <span class="dayHours"></span></label>
                                    <input type="number" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" id="numberOfDays" name="numberOfDays" readonly="" placeholder="Please select number of days" value="0">
                                    <span class="noDayErrors"></span>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <label>@lang('applyLeaveForm.hi.purpose') / @lang('applyLeaveForm.en.purpose')</label>--}}
{{--                                    <textarea class="form-control text-capitalize" rows="3" name="purpose" id="purpose" placeholder="Enter purpose for leave"></textarea>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label>Purpose/Comment</label>
                                    <textarea class="form-control text-capitalize" rows="3" name="purpose" id="purpose" placeholder="Enter comment for leave"></textarea>
                                </div>


                                {{--                                <span class="fileErrors"></span>--}}

                                <input type="hidden" name="newAllDatesArray" id="newAllDatesArray" value="">
                                <input type="hidden" name="weekoffs" id="weekoffs" value="">
                                <input type="hidden" name="excludedDates" id="excludedDates" value="">

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" id="applyLeaveFormSubmit" class="btn btn-primary submit-btn-style">@lang('applyLeaveForm.hi.submit') / @lang('applyLeaveForm.en.submit')</button>
                                {{--                                <a href="{{route('leaves.listAppliedLeaves')}}" class="btn btn-default cancel-btn-styling">@lang('applyLeaveForm.hi.cancel') / @lang('applyLeaveForm.en.cancel')</a>--}}
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <!-- /.row -->
            <!-- Main row -->


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script type="text/javascript">

        $(".halfLeaveDiv").hide();
        $(".hpslFullHalfPayDiv").hide();
        $(".firstSecondHalfDiv").hide();
        $(".encashmentDiv").hide();
        $("#encash_weekoff").hide();

        var allowFormSubmit = {files: 1, filesize: 1, date: 1, time: 1, leaveAccumulation: 1};

        var employeeType = "";
        $('#user_id').on('change',function(){

            var user_id = $('#user_id').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('employee.type') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: user_id
                },

                success: function (data) {
                    // console.log(data)
                    employeeType = data.emp_type
                    if(employeeType == 'Workman') {
                        $("#encash_weekoff").show();
                    }else{
                        $("#encash_weekoff").hide();
                    }
                    // console.log(employeeType);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        var gender = "{{$gender}}";

        if(employeeType == 'Workman'){
            $("#leaveTypeId option[value=7]").prop("disabled",true);  //Quarantine leaves
        }

        if(gender == 'Male'){
            $("#leaveTypeId option[value=8]").prop("disabled",true);  //Maternity leaves
        }else if(gender == 'Female'){
            $("#leaveTypeId option[value=9]").prop("disabled",true);  //Paternity leaves
        }

        $(document).ready(function(){
            $("#applyLeaveForm").validate({
                rules: {
                    "purpose" : {
                        required: true,
                    },
                    "address" : {
                        required: true,
                    },
                    "fromDate" : {
                        required: true,
                    },
                    "toDate" : {
                        required: true,
                    },
                    "fromTime" : {
                        required: true,
                    },
                    "toTime" : {
                        required: true,
                    },
                    "leaveTypeId" : {
                        required: true
                    },
                    'weekoff' : {
                        required: true
                    },
                    'supervisor':{
                        required: true
                    }
                },
                messages: {
                    "purpose" : {
                        required : "Please enter the purpose.",
                    },
                    "address" : {
                        required : 'Please enter the address.',
                    },
                    "fromDate" : {
                        required: 'Please enter from date.',
                    },
                    "toDate" : {
                        required: 'Please enter to date.',
                    },
                    "fromTime" : {
                        required: 'Please enter from time.',
                    },
                    "toTime" : {
                        required: 'Please enter to time.',
                    },
                    "leaveTypeId" : {
                        required: 'Please select a leave type.'
                    },
                    'weekoff' : {
                        required: 'Please select a weekoff date.'
                    },
                    'supervisor':{
                        required: 'Please select a Sanction Officer.'
                    }
                }
            });
        });
    </script>

    <!-- bootstrap time picker -->
    <script src="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>

    <script type="text/javascript">

        //Date picker
        $("#weekoff").datepicker({
            autoclose: true,
            orientation: "bottom",
            multidate: true,
            multidateSeparator: ","
        });

        $("#fromDate").datepicker({
            autoclose: true,
            orientation: "bottom"
        });

        $("#toDate").datepicker({
            autoclose: true,
            orientation: "bottom"
        });

        $("#fromTime").timepicker({
            defaultTime: 'current',
            showInputs: false,
            minuteStep: 30
        });

        $("#toTime").timepicker({
            defaultTime: 'current',
            showInputs: false,
            minuteStep: 30
        });

        $("#fromToTimeDiv").hide();

        $('.selectTime').timepicker().on('changeTime.timepicker',function(e){
            var leaveTypeId = $("#leaveTypeId").val();
            //if enchashment do not make the number of days 0


            var fromTime = $("#fromTime").val();
            var today = new Date();
            var dd = today.getDate();

            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();

            if(dd<10)
            {
                dd='0'+dd;
            }

            if(mm<10)
            {
                mm='0'+mm;
            }

            today = yyyy+'-'+mm+'-'+dd;

            fromTime = today+" "+fromTime;

            var toTime = $("#toTime").val();

            toTime = today+" "+toTime;

            if(Date.parse(fromTime) >= Date.parse(toTime)){
                allowFormSubmit.time = 0;
                $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");
            }else{
                allowFormSubmit.time = 1;
                $(".timeErrors").text("");
                var timeDiff = (Date.parse(toTime) - Date.parse(fromTime))/(1000*60*60);
                var timeDays = 0.125*timeDiff;

                $("#numberOfDays").val(timeDays);



                if(leaveTypeId == 14){  //Short Leave
                    if(employeeType != 'Workman' && timeDays != 0.25){
                        allowFormSubmit.time = 0;
                        $(".timeErrors").text("You can only select a two hour duration.").css("color","#f00");
                        return false;

                    }else if(employeeType == 'Workman' && timeDays < 0.125){
                        allowFormSubmit.time = 0;
                        $(".timeErrors").text("You cannot select less than one hour duration.").css("color","#f00");
                        return false;

                    }else{
                        allowFormSubmit.time = 1;
                        $(".timeErrors").text("");
                    }
                }

                // var totalLeavesVal = Number($("#totalLeavesVal").text());

                if(timeDays > totalLeavesVal){
                    allowFormSubmit.time = 0;
                    $(".timeErrors").text("You do not have enough remaining leaves.").css("color","#f00");
                }else{
                    allowFormSubmit.time = 1;
                    $(".timeErrors").text("");
                }

                //  if(leaveTypeId == 1){  //Cashual Leave
                //     if(number != 0.25){
                //       allowFormSubmit.time = 0;
                //       $(".timeErrors").text("You can only select a two hour duration.").css("color","#f00");
                //       return false;

                //     }else if(employeeType == 'Workman' && timeDays < 0.125){
                //       allowFormSubmit.time = 0;
                //       $(".timeErrors").text("You cannot select less than one hour duration.").css("color","#f00");
                //       return false;

                //     }else{
                //       allowFormSubmit.time = 1;
                //       $(".timeErrors").text("");
                //     }
                //   }


            }
        });

        $("#halfLeave").on("click",function(){
            var checked = $(this).is(":checked");

            $("#toDate").val("");
            $("#fromDate").val("");
            $("#numberOfDays").val("0");
            $('input[type=radio][name=fullHalfPay][value=Half-Pay]').prop('checked',true);
            $('input[type=radio][name=firstSecondHalf][value="First Half"]').prop('checked',true);

            if(checked){
                $("#toDate").prop("disabled",true);
                $(this).val("1");
                $(".firstSecondHalfDiv").show();
            }else{
                $("#toDate").prop("disabled",false);
                $(this).val("0");
                $(".firstSecondHalfDiv").hide();
            }
        });

        var offDatesArray = [];  //For Workman only

        $("#weekoff").on('change',function(){
            var offDates = $(this).val();
            oldOffDatesArray = offDates.split(',');

            offDatesArray = oldOffDatesArray.map(function(offDate){
                var offdt = new Date(offDate);
                return moment(offdt).format('YYYY-MM-DD');
            });

            $("#weekoffs").val(offDatesArray.join());

            $("#fromDate").val("");
            $("#toDate").val("");
            $("#numberOfDays").val("0");

        });

        $('input[type=radio][name=fullHalfPay]').on('change',function() {
            $("#fromDate").val("");
            $("#toDate").val("");
            $("#numberOfDays").val("0");
        });

        $('.selectDate').on('change', function(){


            if(employeeType == 'Workman'){
                if(!$('#encash_Leave').prop('checked')){//check if its not encashable
                    var weekOffs = $("#weekoff").val();
                    weekOffsArray = weekOffs.split(',');
                    if(!weekOffs){
                        $("#fromDate").val("");
                        $("#toDate").val("");
                        $("#numberOfDays").val("0");
                        alert("Please select weekoff(s) first.");
                        return false;
                    }
                }
            }

            var fromDate = $("#fromDate").val();
            var halfLeaveChecked = $("#halfLeave").is(":checked");
            var leaveTypeId = $("#leaveTypeId").val();
            var payStatus = $('input[name=fullHalfPay]:checked').val();

            if(halfLeaveChecked){
                $("#toDate").val(fromDate);

                // if(payStatus == 'Full-Pay' && leaveTypeId == 2){
                //   $("#numberOfDays").val("1");
                // }else{
                //   $("#numberOfDays").val("0.5");
                // }
                // return false;
            }else{
                $("#numberOfDays").val("0");
            }

            var toDate = $("#toDate").val();



            if(!leaveTypeId){
                allowFormSubmit.date = 0;
                $(".dateErrors").text("Please select a leave type.").css("color","#f00");
                return false;
            }else{
                allowFormSubmit.date = 1;
                $(".dateErrors").text("");

                if(leaveTypeId == 4){
                    $("#fromTime").val("9:00 AM");
                    $("#toTime").val("9:00 AM");
                    allowFormSubmit.time = 0;
                    $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");

                }else if(leaveTypeId == 14){
                    $("#fromTime").val("9:00 AM");
                    $("#toTime").val("9:00 AM");
                    allowFormSubmit.time = 0;
                    $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");

                    $("#toDate").val(fromDate);

                    toDate = fromDate;

                    var checkFromDt = new Date(fromDate);
                    var checkCurrentDt = new Date();

                    if(checkFromDt.getMonth() != checkCurrentDt.getMonth()){
                        allowFormSubmit.date = 0;
                        $(".dateErrors").text("Please select a date from current month only.").css("color","#f00");
                        return false;
                    }else{
                        allowFormSubmit.date = 1;
                        $(".dateErrors").text("");
                    }

                }else{
                    $("#fromTime").val("");
                    $("#toTime").val("");
                    allowFormSubmit.time = 1;
                    $(".timeErrors").text("");
                }
            }

            if(Date.parse(fromDate) > Date.parse(toDate)){
                allowFormSubmit.date = 0;
                $(".dateErrors").text("Please select valid dates.").css("color","#f00");

            }else{
                allowFormSubmit.date = 1;
                $(".dateErrors").text("");

                var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
                var diffDays = Math.round(Math.abs((Date.parse(toDate) - Date.parse(fromDate))/(oneDay)));

                if(isNaN(diffDays)){

                    $("#numberOfDays").val("0");


                }else{

                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");

                    diffDays = diffDays + 1;

                    //Calculate the saturdays & sundays between leaves for M&S type
                    var result = [];  //Sundays & Saturdays

                    if(fromDate != toDate){
                        var allDates = enumerateDaysBetweenDates(fromDate, toDate);

                        $.each(allDates,function(key,value){
                            if(employeeType != "Workman"){
                                var dt = new Date(value);

                                if(dt.getDay() == 6 || dt.getDay() == 0)
                                {
                                    result.push(value);
                                }

                            }else{  //Weekoffs for Workman

                                if(offDatesArray.includes(value)){
                                    result.push(value);
                                }
                            }
                        });

                    }else if(fromDate == toDate){
                        var dt = new Date(fromDate);

                        if(employeeType != "Workman"){
                            if(dt.getDay() == 6 || dt.getDay() == 0)
                            {
                                dt = moment(dt).format("YYYY-MM-DD");
                                result.push(dt);
                            }

                        }else{
                            dt = moment(dt).format("YYYY-MM-DD");

                            if(offDatesArray.includes(dt)){
                                result.push(dt);
                            }

                        }

                    }

                    var exclude = ['8','9','10'];

                    if(!exclude.includes(leaveTypeId)){
                        diffDays = diffDays - result.length;
                    }

                    //End Calculation of sundays between leaves
                    console.log("result: ",result);
                    //Calculate holidays that are not Sundays
                    // console.log(fromDate, toDate);
                    var allDatesArray = enumerateDaysBetweenDates(fromDate, toDate);

                    // $.each(result, function(key, value){
                    //   result[key] = moment(value).format("YYYY-MM-DD");
                    // });

                    console.log("allDatesArray: ",allDatesArray);

                    $.ajax({
                        type: 'POST',
                        url: "{{route('leaves.holidaysBetweenLeaves')}}",
                        data: {allDatesArray: allDatesArray, employeeType: employeeType, offDatesArray: offDatesArray},
                        success: function(holidays){

                            if(!exclude.includes(leaveTypeId)){
                                diffDays = diffDays - Number(holidays.length);
                            }
                            console.log("holidays: ",holidays);
                            if(diffDays <= 0){
                                diffDays = 0;
                                allowFormSubmit.date = 0.0;
                                $(".noDayErrors").text("Number of days cannot be zero.").css("color","#f00");
                                var newAllDatesArray = "";

                            }else{
                                allowFormSubmit.date = 1;
                                $(".noDayErrors").text("");

                                $.each(holidays, function(key, value){
                                    result.push(value);
                                });

                                //console.log("result: ",result);

                                var newAllDatesArray = [];

                                if(!exclude.includes(leaveTypeId)){
                                    $.each(allDatesArray, function(key, value){
                                        if(!result.includes(value)){
                                            newAllDatesArray.push(value);
                                        }
                                    });
                                }else{
                                    newAllDatesArray = allDatesArray;
                                }

                                newAllDatesArray = newAllDatesArray.join();
                            }

                            //console.log("newAllDatesArray: ",newAllDatesArray);

                            $("#newAllDatesArray").val(newAllDatesArray);
                            $("#excludedDates").val(result.join());

                            if(leaveTypeId == 2){
                                let dayMul = 1;
                                if(payStatus == 'Full-Pay'){
                                    if(halfLeaveChecked && diffDays > 0){
                                        $("#numberOfDays").val("1");
                                    }else{
                                        $("#numberOfDays").val(diffDays*2);
                                    }
                                    dayMul = 2;
                                }
                                else{
                                    if(halfLeaveChecked && diffDays > 0){
                                        $("#numberOfDays").val("0.5");
                                    }else{
                                        $("#numberOfDays").val(diffDays);
                                    }
                                }
                                let totalLeavesVal = parseFloat($("#totalLeavesVal").html())

                                let compDays = dayMul * diffDays

                                if(compDays > totalLeavesVal){
                                    allowFormSubmit.date = 0.0;
                                    $(".noDayErrors").text("Days cannot exceed limit " + totalLeavesVal).css("color","#f00");
                                }
                                else{
                                    allowFormSubmit.date = 1;
                                    $(".noDayErrors").text("");
                                }
                            }else{

                                if(halfLeaveChecked && diffDays > 0){
                                    $("#numberOfDays").val("0.5");
                                }else{
                                    $("#numberOfDays").val(diffDays);
                                }
                            }

                            // if(halfLeaveChecked){
                            //   if(payStatus == 'Full-Pay' && leaveTypeId == 2){
                            //     $("#numberOfDays").val("1");
                            //   }else{
                            //     $("#numberOfDays").val("0.5");
                            //   }
                            // }

                            // compensatory off
                            if(diffDays > 1 && leaveTypeId == 4){
                                $("#fromTime").val("");
                                $("#toTime").val("");
                                $("#fromToTimeDiv").hide();

                                var totalLeavesVal = Number($("#totalLeavesVal").text());

                                if(diffDays > totalLeavesVal){
                                    allowFormSubmit.date = 0;
                                    $(".dateErrors").text("You do not have enough remaining leaves.").css("color","#f00");
                                }else{
                                    allowFormSubmit.date = 1;
                                    $(".dateErrors").text("");
                                }

                            }else if(diffDays == 1 && leaveTypeId == 4){
                                $("#fromToTimeDiv").show();

                            }else if(diffDays == 1 && leaveTypeId == 14){
                                $("#fromToTimeDiv").show();
                            }

                        }
                    });//End Calculate holidays that are not Sundays

                }
            }
        });

        function secondAndFourthSaturday(newDt){
            var month = newDt.getMonth();
            var dates = [];
            var result = [];
            newDt.setDate(1);

            while(newDt.getDay() !== 6){
                newDt.setDate(newDt.getDate() + 1);
            }

            while (newDt.getMonth() === month) {
                dates.push(new Date(newDt.getTime()));
                newDt.setDate(newDt.getDate() + 7);
            }

            $.each(dates,function(key,value){
                if(key % 2 != 0 && result.length < 2){
                    result.push(moment(value).format("YYYY-MM-DD"));
                }
            });

            return result;
        }

        function enumerateDaysBetweenDates(startDate, endDate) {
            startDate = moment(startDate,"MM-DD-YYYY");
            endDate = moment(endDate,"MM-DD-YYYY");
            var now = startDate.clone();
            var dates = [];


            while (now.isSameOrBefore(endDate)) {
                dates.push(now.format('YYYY-MM-DD'));
                now.add(1, 'days');
            }
            return dates;
        };

        function leaveTypeSpecificConditions(leaveTypeId,numberOfDays,gender,employeeType)
        {
            var input = document.getElementById('alChooseFileBtn');
            var output = true;

            if(leaveTypeId == 6){   //Blood Donation
                if(numberOfDays > 1){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 1.").css("color","#f00");
                    output = false;
                }else{
                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");
                }

                if(input.files.length == 0){
                    $(".fileErrors").text("Please upload a medical certificate.").css("color","#f00");
                    allowFormSubmit.files = 0;
                    output = false;
                }else{
                    allowFormSubmit.files = 1;
                    $(".fileErrors").text("");
                }

            }
            else if(leaveTypeId == 7){  //Quarantine leaves
                if(numberOfDays > 15){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 15.").css("color","#f00");
                    output = false;
                }else{
                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");
                }

                if(input.files.length == 0){
                    $(".fileErrors").text("Please upload a medical certificate.").css("color","#f00");
                    allowFormSubmit.files = 0;
                    output = false;
                }else{
                    allowFormSubmit.files = 1;
                    $(".fileErrors").text("");
                }

            }
            else if(leaveTypeId == 8 || leaveTypeId == 9){  //Maternity and Paternity leaves
                if(numberOfDays > 180){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 180.").css("color","#f00");
                    output = false;
                }else{
                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");
                }

            }
            else if(leaveTypeId == 11){  //EL Encashable
                if($("#encash_Leave").prop('chceked')){
                    if(!validateElEncashLeaveLimit()){
                        allowFormSubmit.date = 0;
                        $(".noDayErrors").text("Number of days cannot be greater than yearly balance.").css("color","#f00");
                        output = false;
                    }else{
                        allowFormSubmit.date = 1;
                        $(".noDayErrors").text("");
                    }
                }

            }
            else if(leaveTypeId == 10){   //EOL leaves
                if(input.files.length == 0){
                    $(".fileErrors").text("Please upload a medical certificate.").css("color","#f00");
                    allowFormSubmit.files = 0;
                    output = false;
                }else{
                    allowFormSubmit.files = 1;
                    $(".fileErrors").text("");
                }

            }
            else if(leaveTypeId == 5){  //Sterlisation leaves
                if(numberOfDays > 9 && gender == 'Male'){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 9.").css("color","#f00");
                    output = false;
                }else if(numberOfDays > 14 && gender == 'Female'){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 14.").css("color","#f00");
                    output = false;
                }else{yearly
                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");
                }

            }
            else if(leaveTypeId == 2){  //HPSL
                let leaveDuration  = $("input[name='fullHalfPay']:checked").val();
                let multiplier = 1;
                if(leaveDuration == 'Full-Pay'){
                    multiplier = 2;
                }

                if(input.files.length == 0 && (numberOfDays >=(4*multiplier) && numberOfDays < (6*multiplier))){
                    $(".fileErrors").text("Please upload a medical certificate.").css("color","#f00");
                    allowFormSubmit.files = 0;
                    output = false;
                }else if(input.files.length == 0 && numberOfDays >= (6*multiplier)){
                    $(".fileErrors").text("Please upload both medical & fitness certificates.").css("color","#f00");
                    allowFormSubmit.files = 0;
                    output = false;
                }else{
                    allowFormSubmit.files = 1;
                    $(".fileErrors").text("");
                }

            }else if(leaveTypeId == 12){  //RH
                if(numberOfDays > 2){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 2.").css("color","#f00");
                    output = false;
                }else{
                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");
                }

            }else if(leaveTypeId == 1){   //CL
                if(numberOfDays > 7 && employeeType == 'Workman'){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 7.").css("color","#f00");
                    output = false;
                }else if(numberOfDays > 7 && employeeType != 'Workman'){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("Number of days cannot be greater than 7.").css("color","#f00");
                    output = false;
                }else if(totalLeavesVal < 0){
                    allowFormSubmit.date = 0;
                    $(".noDayErrors").text("You Don't Have Remaining Leave .").css("color","#f00");
                    output = false;
                }
                else{
                    allowFormSubmit.date = 1;
                    $(".noDayErrors").text("");
                }
            }

            return output;
        }

        $("#applyLeaveFormSubmit").on('click',function(){
            var leaveTypeId = $("#leaveTypeId").val();
            var numberOfDays = $("#numberOfDays").val();

            // var output = leaveTypeSpecificConditions(leaveTypeId,numberOfDays,gender,employeeType);
            //
            // if(allowFormSubmit.leaveAccumulation == 0 || allowFormSubmit.files == 0 || allowFormSubmit.filesize == 0 || allowFormSubmit.date == 0 || allowFormSubmit.time == 0 || output == false){
            //     return false;
            // }
            //
            // if(allowFormSubmit.leaveAccumulation == 1 || allowFormSubmit.files == 1 || allowFormSubmit.filesize == 1 || allowFormSubmit.date == 1 || allowFormSubmit.time == 1 || output == true){
                $("#applyLeaveForm").submit();
            // }
        });


        const validateElEncashLeaveLimit = function (){
            let noOfDaysELEncash = parseFloat($("#numberOfDays").val())
            let elEncashLimit = parseFloat($("#yearlyBalanceLeavesVal").text())
            let diff = noOfDaysELEncash - elEncashLimit

            if(diff > 0){
                alert('Leave encashment cannot be more than yearly limi i.e ' + elEncashLimit)
                $("#numberOfDays").val('')
                return false;
            }
        }
        $("#numberOfDays").on('keyup', function(){
            validateElEncashLeaveLimit()
        })
        $('#leaveTypeId').on('change',function(){
            var leaveTypeId = $(this).val();
            $("#encash_Leave").prop("checked", true);


            if(leaveTypeId == 1 || leaveTypeId == 2){
                $(".halfLeaveDiv").show();

            }else if(leaveTypeId == 14){
                $(".halfLeaveDiv").hide();
                $('#halfLeave').prop('checked',false);
                $('#halfLeave').val('0');
                $(".firstSecondHalfDiv").hide();

            }else{
                $(".halfLeaveDiv").hide();
                $('#halfLeave').prop('checked',false);
                $('#halfLeave').val('0');
                $(".firstSecondHalfDiv").hide();

            }

            if(leaveTypeId == 2){
                $(".hpslFullHalfPayDiv").show();
            }else{
                $(".hpslFullHalfPayDiv").hide();
            }



            if(leaveTypeId == 11){
                $(".encashmentDiv").show();
                $("#numberOfDays").removeAttr('readonly');
                $("#numberOfDays").attr('required', true);
                $("#encash_toDate").hide();
                $("#encash_weekoff").hide();

            }else{
                $(".encashmentDiv").hide();
                $("#numberOfDays").attr('readonly', true);
                $("#numberOfDays").removeAttr('required');
                $("#encash_toDate").show();
                $("#encash_weekoff").show();
            }

            $('input:radio[name=encashmentStatus]').change(function() {

                if (this.value == '0') {
                    $("#encash_toDate").show();
                    $("#encash_weekoff").show();

                }
                else if (this.value == '1') {
                    $("#encash_toDate").hide();
                    $("#encash_weekoff").hide();
                }
            });

            $("#fromTime").val("");
            $("#toTime").val("");
            $("#toDate").val("");
            $("#fromDate").val("");
            $("#numberOfDays").val("0");
            $("#halfLeave").prop("checked",false);

            if(leaveTypeId){
                $("#toDate").prop("disabled",false);

                if(leaveTypeId == 4){   //Compensatory leave
                    $("#fromTime").val("9:00 AM");
                    $("#toTime").val("9:00 AM");
                    allowFormSubmit.time = 0;
                    $("#fromToTimeDiv").show();
                    $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");

                }else if(leaveTypeId == 14){  //Short Leave
                    $("#fromTime").val("9:00 AM");
                    $("#toTime").val("9:00 AM");
                    allowFormSubmit.time = 0;
                    $("#fromToTimeDiv").show();
                    $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");

                    $("#toDate").prop("disabled",true);

                }else{
                    allowFormSubmit.time = 1;
                    $(".timeErrors").text("");
                    $("#fromTime").val("");
                    $("#toTime").val("");
                    $("#fromToTimeDiv").hide();
                }

                if(leaveTypeId == 16){   //extra duty leave
                    $("#fromTime").val("9:00 AM");
                    $("#toTime").val("9:00 AM");
                    allowFormSubmit.time = 0;
                    $("#fromToTimeDiv").show();
                    $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");

                }else if(leaveTypeId == 14){  //Short Leave
                    $("#fromTime").val("9:00 AM");
                    $("#toTime").val("9:00 AM");
                    allowFormSubmit.time = 0;
                    $("#fromToTimeDiv").show();
                    $(".timeErrors").text("From Time should be less than To Time.").css("color","#f00");

                    $("#toDate").prop("disabled",true);

                }else{
                    allowFormSubmit.time = 1;
                    $(".timeErrors").text("");
                    $("#fromTime").val("");
                    $("#toTime").val("");
                    $("#fromToTimeDiv").hide();
                }

                {{--$.ajax({--}}
                {{--    type: 'POST',--}}
                {{--    url: "{{route('leaves.leaveTypeWiseLeaveAccumulation')}}",--}}
                {{--    data: {leaveTypeId: leaveTypeId},--}}
                {{--    success: function(result){--}}

                {{--        if(result.status){--}}
                {{--            $("#totalLeavesVal").text(result.totalRemainingCount);--}}
                {{--            $("#yearlyBalanceLeavesVal").text(result.yearlyBalanceLeaves);--}}
                {{--            $("#processingLeavesVal").text(result.processingLeavesCount);--}}
                {{--            if(leaveTypeId == 4){--}}
                {{--                $(".yearlyLeavesTaken").text("Total Leaves Taken:");--}}
                {{--                $(".dayHours").text("(1 Day = 8 Hours)");--}}
                {{--            }else if(leaveTypeId == 14){--}}
                {{--                $(".yearlyLeavesTaken").text("Monthly Leaves Taken:");--}}
                {{--                $(".dayHours").text("(1 Day = 8 Hours)");--}}
                {{--            }else{--}}
                {{--                $(".yearlyLeavesTaken").text("Yearly Leaves Taken:");--}}
                {{--                $(".dayHours").text("");--}}
                {{--            }--}}

                {{--            $("#yearlyLeavesTakenVal").text(result.yearlyLeavesTaken);--}}
                {{--            allowFormSubmit.leaveAccumulation = 1;--}}
                {{--        }else{--}}
                {{--            if(result.allow){--}}
                {{--                $("#totalLeavesVal").text("0");--}}
                {{--                $("#yearlyBalanceLeavesVal").text("0");--}}
                {{--                $("#processingLeavesVal").text("0");--}}
                {{--                $("#yearlyLeavesTakenVal").text("0");--}}
                {{--                $(".dayHours").text("");--}}
                {{--                allowFormSubmit.leaveAccumulation = 1;--}}
                {{--            }else{--}}
                {{--                allowFormSubmit.leaveAccumulation = 0;--}}
                {{--                alert("For the selected leave type, you do not have leaves credited in the database. Please contact the administrator.");--}}
                {{--            }--}}

                {{--        }--}}
                {{--    }--}}
                {{--});--}}
            }
        });

        $("#alChooseFileBtn").on('change', function(){
            var input = document.getElementById('alChooseFileBtn');
            var output = document.getElementById('fileList');
            var filesize = 1048576;  // 1MB

            if(input.files.length > 5){
                $(".fileErrors").text("You cannot select more than 5 files.").css("color","#f00");
                allowFormSubmit.files = 0;
                return false;
            }else{
                $(".fileErrors").text("");
                allowFormSubmit.files = 1;
            }

            output.innerHTML = '<ul>';
            for (var i = 0; i < input.files.length; ++i) {

                if(input.files[i].size > filesize){
                    allowFormSubmit.filesize = 0;
                    $(".fileErrors").text("You cannot select a file of size more than 1 MB.").css("color","#f00");
                    output.innerHTML = "";
                    return false;
                    break;
                }else{
                    allowFormSubmit.filesize = 1;
                    $(".fileErrors").text("");
                }
                output.innerHTML += '<li>' + input.files[i].name + '</li>';
            }
            output.innerHTML += '</ul>';
        });

    </script>


@endsection
