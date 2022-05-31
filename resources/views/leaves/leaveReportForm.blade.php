@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Generate Leave Report</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
             
            <!-- form start -->
            <form id="leaveReportForm" method="POST" action="{{route('leaves.generateLeaveReport')}}">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="fromDate">From Date</label>
                  <div class="input-group date single-input-lbl">
                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right date-input selectDate input-sm basic-detail-input-style" id="fromDate" name="fromDate" readonly>
                    <span class="dateErrors"></span>
                  </div>
                  <!-- /.input group -->
                </div>

                <div class="form-group">
                  <label for="toDate">To Date</label>
                  <div class="input-group date single-input-lbl">
                    <div class="input-group-addon date-icon input-sm basic-detail-input-style">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right date-input selectDate input-sm basic-detail-input-style" id="toDate" name="toDate" readonly>
                  </div>
                  <!-- /.input group -->
                </div>

                <div class="form-group">
                    <label for="unitId">Unit</label>
                      <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="unitId" id="unitId">
                        <option value="" selected disabled>Please Select Unit</option>
                        @if(!$units->isEmpty())
                          @foreach($units as $unit)
                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                          @endforeach
                        @endif
                    </select>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary submit-btn-style leaveReportFormSubmit">Submit</button>
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

  $("#leaveReportForm").validate({
      rules: {
        "fromDate" : {
          required: true,
        },
        "toDate" : {
          required: true,
        },
        "unitId" : {
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
        "unitId" : {
            required : "Please select a unit.",
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

  $(".leaveReportFormSubmit").on('click',function(){
    if(allowFormSubmit.date == 0){
      return false;
    }else{
      $("#leaveReportForm").submit();
    }
  });
  </script>

  @endsection