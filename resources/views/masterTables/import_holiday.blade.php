@extends('admins.layouts.app')



@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="text-center">Import Holiday Form</h1>
            <ol class="breadcrumb">
                <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li> -->
                <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
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
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <form action="{{route('masterTables.import_holiday_save')}}" method="POST" id="holidayForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="sessionName">@lang('holidayForm.hi.sessionName') / @lang('holidayForm.en.sessionName')<span style="color: red">*</span></label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="sessionName" id="sessionName" required>
                                        <option value="" selected disabled>Please select Session</option>
                                        @if(!$data['sessions']->isEmpty())
                                            @foreach($data['sessions'] as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="sessionName">@lang('holidayForm.hi.unitName') / @lang('holidayForm.en.unitName')<span style="color: red">*</span></label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="unitName" id="unitName" required>
                                        <option value="" selected disabled>Please select Unit</option>
                                        @if(!$data['units']->isEmpty())
                                            @foreach($data['units'] as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="holidayName">Import Holiday List<span style="color: red">*</span></label>
                                    <input type="file" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style text-capitalize" id="holidayName" name="holiday_file"required>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-primary holidayFormSubmit">@lang('holidayForm.hi.submit') / @lang('holidayForm.en.submit')</button>
                                <a href="{{route('masterTables.listHolidays')}}" class="btn btn-default">@lang('holidayForm.hi.cancel') / @lang('holidayForm.en.cancel')</a>
                            </div>
                        </form>

                        <a href="{{ asset('public/template/ImportHolidayTemplate.xls') }}" style="float: right">Download Template</a>
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
        var allowFormSubmit = {date: 1};
        var defSessionId = "{{@$data['holiday']->session_id}}";

        if(defSessionId){
            $("#sessionName").val(defSessionId);
        }

        var defUnitId = "{{@$data['holiday']->unit_id}}";

        if(defUnitId){
            $("#unitName").val(defUnitId);
        }

        $("#holidayForm").validate({
            rules: {
                "fromDate" : {
                    required: true,
                },
                "toDate" : {
                    required: true,
                },
                "holidayName" : {
                    required: true,
                },
                "description" : {
                    required: true,
                },
                "sessionName" : {
                    required: true,
                },
                "unitName" : {
                    required: true,
                }
            },
            messages: {
                "fromDate" : {
                    required : "Please select from date.",
                },
                "toDate" : {
                    required : "Please select to date.",
                },
                "holidayName" : {
                    required : "Please enter holiday name.",
                },
                "description" : {
                    required: "Please enter holiday description."
                },
                "sessionName" : {
                    required: "Please select a session."
                },
                "unitName" : {
                    required: "Please select a unit."
                }
            }
        });

        //Date picker
        $('#fromDate').datepicker({
            autoclose: true,
            orientation: "bottom"
        });

        $('#toDate').datepicker({
            autoclose: true,
            orientation: "bottom"
        });

        $(".selectDate").on('change',function(){
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();

            if(Date.parse(fromDate) > Date.parse(toDate)){
                allowFormSubmit.date = 0;
                $(".dateErrors").text("Please select valid dates.").css("color","#f00");
            }else{
                allowFormSubmit.date = 1;
                $(".dateErrors").text("");
            }

        });

        $(".holidayFormSubmit").on('click',function(){
            if(allowFormSubmit.date == 0){
                return false;
            }else{
                $("#holidayForm").submit();
            }
        });
    </script>

@endsection
