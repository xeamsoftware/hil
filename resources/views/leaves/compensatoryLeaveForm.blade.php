@extends('admins.layouts.app')

@section('content')

    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="text-center">Add Compensatory Leave Form</h1>
            <ol class="breadcrumb">
                <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->

            <div class="row">
                <div class="pad margin no-print">
                    <div class="callout callout-danger" style="margin-bottom: 0!important;">
                        <span><strong>Note:&nbsp;&nbsp;</strong></span>
                        <span><em>Please select the time at which you started overtime in the In-Time field.</em></span>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-primary">
                        <form action="{{route('leaves.saveCompensatoryLeave')}}" method="POST" id="compensatoryLeaveForm" enctype="multipart/form-data">
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
                                @endif

                                <div class="form-group">
                                    <label for="leaveType">@lang('applyLeaveForm.hi.leaveType') / @lang('applyLeaveForm.en.leaveType')</label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="leaveType" id="leaveType">
                                        <option value="Compensatory Leave" selected>Compensatory Leave</option>
                                        <option value="Call for Extra Duty Leave">Call for Extra Duty Leave</option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="onDate">@lang('compensatoryLeaveForm.hi.onDate') / @lang('compensatoryLeaveForm.en.onDate')</label>
                                    <div class="input-group date single-input-lbl">
                                        <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control selectDate pull-right date-input input-sm basic-detail-input-style al-date-input" name="onDate" id="onDate" placeholder="28/11/2017" readonly>
                                    </div>
                                    <span class="dateErrors"></span>
                                </div>

                                <div class="row" id="fromToTimeDiv">
                                    <div class="col-md-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <label for="fromTime">@lang('compensatoryLeaveForm.hi.inTime') / @lang('compensatoryLeaveForm.en.inTime')</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>

                                                    <input type="text" class="form-control selectTime input-sm basic-detail-input-style" placeholder="12:00 PM" name="inTime" id="inTime">
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
                                                <label for="toTime">@lang('compensatoryLeaveForm.hi.outTime') / @lang('compensatoryLeaveForm.en.outTime')</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>

                                                    <input type="text" class="form-control selectTime input-sm basic-detail-input-style" placeholder="12:00 PM" name="outTime" id="outTime">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="numberOfHours">@lang('compensatoryLeaveForm.hi.numberOfHours') / @lang('compensatoryLeaveForm.en.numberOfHours')</label>
                                    <input type="text" class="form-control input-sm basic-detail-input-style" placeholder="0" name="numberOfHours" id="numberOfHours" readonly>
                                    <span class="hourErrors"></span>
                                </div>

                                <div class="form-group">
                                    <label for="compensatoryDocument">@lang('compensatoryLeaveForm.hi.compensatoryDocument') / @lang('compensatoryLeaveForm.en.compensatoryDocument')</label>
                                    <input type="file" class="form-control input-sm basic-detail-input-style" name="leaveDocument" id="compensatoryDocument">
                                </div>

                                <div class="form-group">
                                    <label for="supervisor">@lang('applyLeaveForm.hi.supervisor') / @lang('applyLeaveForm.en.supervisor')</label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="supervisor" id="supervisor">
                                        <option value="" selected disabled>Please select a Sanction Officer</option>
                                        @if(!$supervisors->isEmpty())
                                            @foreach($supervisors as $supervisor)
                                                <option value="{{$supervisor->id}}">{{@$supervisor->first_name}} {{@$supervisor->middle_name}} {{@$supervisor->last_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="description">@lang('compensatoryLeaveForm.hi.description') / @lang('compensatoryLeaveForm.en.description')</label>
                                    <textarea class="form-control text-capitalize" rows="3" name="description" id="description" placeholder="Enter description"></textarea>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-primary" id="compensatoryLeaveFormSubmit">@lang('compensatoryLeaveForm.hi.submit') / @lang('compensatoryLeaveForm.en.submit')</button>
                                <a href="{{route('masterTables.listQualifications')}}" class="btn btn-default">@lang('compensatoryLeaveForm.hi.cancel') / @lang('compensatoryLeaveForm.en.cancel')</a>
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

    <!-- bootstrap time picker -->
    <script src="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>

    <script type="text/javascript">
        var allowFormSubmit = {time: 1, hours: 1};

        $("#compensatoryLeaveForm").validate({
            rules : {
                "leaveType" : {
                    required : true
                },
                "onDate" : {
                    required : true
                },
                "numberOfHours" : {
                    required : true
                },
                "description" : {
                    required : true
                },
                "inTime" : {
                    required : true
                },
                "outTime" : {
                    required : true
                },
                'supervisor':{
                    required: true
                }
            },
            messages : {
                "leaveType" : {
                    required : "Please Select Leave Type"
                },
                "onDate" : {
                    required : 'Please select date.'
                },
                "description" : {
                    required : 'Please enter description.'
                },
                "inTime" : {
                    required : 'Please select in time.'
                },
                "outTime" : {
                    required : 'Please select out time.'
                },
                'supervisor':{
                    required: 'Please select a Sanction Officer.'
                }

            }
        });

        $("#onDate").datepicker({
            autoclose: true,
            orientation: "bottom"
        });

        $("#inTime").timepicker({
            defaultTime: 'current',
            showInputs: false,
            minuteStep: 10
        });

        $("#outTime").timepicker({
            defaultTime: 'current',
            showInputs: false,
            minuteStep: 10
        });
    </script>

    <script type="text/javascript">
        $("#onDate").on('change', function(){
            $("#outTime").val("");
            $("#inTime").val("");
            $("#numberOfHours").val("0");
        });

        $(".selectTime").on('changeTime.timepicker', function(e){
            var inTime = $("#inTime").val();
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
            inTime = today+" "+inTime;

            var outTime = $("#outTime").val();
            outTime = today+" "+outTime;

            if(Date.parse(inTime) >= Date.parse(outTime)){
                allowFormSubmit.time = 0;
                $(".timeErrors").text("In Time should be less than Out Time.").css("color","#f00");
            }else{
                allowFormSubmit.time = 1;
                $(".timeErrors").text("");
                var timeDiff = (Date.parse(outTime) - Date.parse(inTime))/(1000*60*60);
                console.log('hours: ',timeDiff);

                let empType = "{{ Auth::user()->employee_type }}";
                let minHrs = 4;
                if(empType === 'Workman'){
                    minHrs = 2;
                }
                if(timeDiff < minHrs){
                    allowFormSubmit.hours = 0;
                    $(".hourErrors").text("Number of hours should be greater than or equal to "+minHrs+".").css("color","#f00");

                    $("#numberOfHours").val(timeDiff);

                }
                    // else if(timeDiff > 8){
                    //   allowFormSubmit.hours = 0;
                    //   $(".hourErrors").text("Number of hours should be less than or equal to 8.").css("color","#f00");
                    //
                    //   $("#numberOfHours").val(timeDiff);
                    //
                // }
                else{

                    allowFormSubmit.hours = 1;
                    $(".hourErrors").text("");

                    var roundTimeDiff = Math.round(timeDiff);

                    if(roundTimeDiff <= timeDiff){
                        $("#numberOfHours").val(roundTimeDiff);

                    }else{  //Round 4.8333 to 4.5
                        roundTimeDiff = Math.floor(timeDiff) + 0.5;
                        $("#numberOfHours").val(roundTimeDiff);

                    }

                }
            }
        });

        $("#compensatoryLeaveFormSubmit").on('click', function(){
            if(allowFormSubmit.time == 0 || allowFormSubmit.hours == 0){
                return false;
            }else{
                $("#compensatoryLeaveForm").submit();
            }
        });

    </script>
@endsection
