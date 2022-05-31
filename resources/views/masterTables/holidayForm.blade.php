@extends('admins.layouts.app')



@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="text-center">Holiday Form</h1>
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
                        <form action="{{route('masterTables.saveHoliday')}}" method="POST" id="holidayForm">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="sessionName">@lang('holidayForm.hi.sessionName') / @lang('holidayForm.en.sessionName')</label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="sessionName" id="sessionName">
                                        <option value="" selected disabled>Please select Session</option>
                                        @if(!$data['sessions']->isEmpty())
                                            @foreach($data['sessions'] as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="sessionName">@lang('holidayForm.hi.unitName') / @lang('holidayForm.en.unitName')</label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="unitName" id="unitName">
                                        <option value="" selected disabled>Please select Unit</option>
                                        @if(!$data['units']->isEmpty())
                                            @foreach($data['units'] as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="sessionName">@lang('holidayForm.hi.type') / @lang('holidayForm.en.type')</label>
                                    <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="holiday_type" id="holiday_type">
                                        <option value="" selected disabled>Please select Type</option>
                                        <option value="RH">Restricted Holiday</option>
                                        <option value="GH">Gazetted Holiday</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="holidayName">@lang('holidayForm.hi.holidayName') / @lang('holidayForm.en.holidayName')</label>
                                    <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style text-capitalize" id="holidayName" name="holidayName" placeholder="Please enter holiday name" value="{{@$data['holiday']->name}}">
                                </div>

                                <div class="form-group">
                                    <label for="fromDate">@lang('holidayForm.hi.fromDate') / @lang('holidayForm.en.fromDate')</label>
                                    <div class="input-group date single-input-lbl">
                                        <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control selectDate pull-right date-input input-sm basic-detail-input-style" name="fromDate" id="fromDate" value="@if(@$data['holiday']){{date('m/d/Y',strtotime(@$data['holiday']->from_date))}}@endif" @if(@$data['holiday']){{"disabled"}}@else{{"readonly"}}@endif>
                                        <span class="dateErrors"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="toDate">@lang('holidayForm.hi.toDate') / @lang('holidayForm.en.toDate')</label>
                                    <div class="input-group date single-input-lbl">
                                        <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control selectDate pull-right date-input input-sm basic-detail-input-style" name="toDate" id="toDate" value="@if(@$data['holiday']){{date('m/d/Y',strtotime(@$data['holiday']->to_date))}}@endif" @if(@$data['holiday']){{"disabled"}}@else{{"readonly"}}@endif>
                                    </div>
                                </div>

                                <input type="hidden" name="action" value="{{$data['action']}}">

                                @if(@$data['holiday'])
                                    <input type="hidden" name="holidayId" value="{{@$data['holiday']->id}}">
                                @endif

                                <div class="form-group">
                                    <label>@lang('holidayForm.hi.description') / @lang('holidayForm.en.description')</label>
                                    <textarea class="form-control text-capitalize" name="description" id="description" rows="3" placeholder="Enter holiday description here">{{@$data['holiday']->description}}</textarea>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-primary holidayFormSubmit">@lang('holidayForm.hi.submit') / @lang('holidayForm.en.submit')</button>
                                <a href="{{route('masterTables.listHolidays')}}" class="btn btn-default">@lang('holidayForm.hi.cancel') / @lang('holidayForm.en.cancel')</a>
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
