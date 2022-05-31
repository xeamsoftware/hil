@extends('admins.layouts.app')



@section('content')



<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper content-aside">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Leave Approval List

      </h1>

      <ol class="breadcrumb">

        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <!-- Small boxes (Stat box) -->

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <!-- <div class="box-header">

              <h3 class="box-title">Employees List</h3>

            </div> -->

            <!-- /.box-header -->

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

            <div class="box-body">

              <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                    {{@$selectedStatus}}
                    <span class="caret"></span></button>

                    <ul class="dropdown-menu">
                      <li><a href='{{url("leaves/appliedLeaveApprovals/pending")}}'>In-progress</a></li>
                      <li><a href='{{url("leaves/appliedLeaveApprovals/approved")}}'>Approved</a></li>
                      <li><a href='{{url("leaves/appliedLeaveApprovals/rejected")}}'>Rejected</a></li>
                    </ul>
                </div>

              <table id="listAppliedLeaveApprovals" class="table table-bordered table-striped">



                <thead class="table-heading-style">



                <tr>

                  <th>S.No.</th>

                  <th>Applied At</th>

                  <th>Applied By</th>

                  <th>Leave Type</th>

                  <th>From Date</th>

                  <th>To Date</th>

                  <th>No Of Days</th>

                  <th>Final Status</th>

                  <th>Messages</th>

                  <th>Action</th>

                </tr>



                </thead>



                <tbody>



                @php

                  $counter = 0;

                  $currentYearMonth = date("Ym");

                @endphp



                @foreach($data as $key => $value)



                <tr>

                  <td>{{++$counter}}</td>

                  <td>{{date("d/m/Y h:i A",strtotime($value->applied_leave_created_at))}}</td>

                  <td><a href="javascript:void(0)" class="dashboardDetails" data-userid="{{$value->user_id}}" title="more details" details">{{$value->first_name}} {{$value->middle_name}} {{$value->last_name}}</a></td>

                  <td><a href="javascript:void(0)" class="additionalAppliedLeaveDetails" data-appliedleaveid="{{$value->applied_leave_id}}" title="more details">{{$value->leave_type_name}}</a></td>

                  <td>{{date("d/m/Y",strtotime($value->from_date))}}</td>
                  <td>
                  @if($value->to_date == "" || !strtotime($value->to_date) || $value->to_date =='0000-00-00')
                          NA
                    @elseif($value->to_date)
                      {{date("d/m/Y",strtotime($value->to_date))}}
                  @endif
                  </td>

                  <td>{{$value->number_of_days}}</td>

                  <td>

                    @if($value->final_status == '0')

                      <span class="label label-danger">Not Approved</span>

                    @else

                      <span class="label label-success">Approved</span>

                    @endif

                  </td>

                  <td><span class="chatModal" data-appliedleaveid="{{$value->applied_leave_id}}"><a href="javascript:void(0)"><i class="fa fa-envelope fa-2x"></i></a></span></td>

                  <td>

                        <div class="dropdown">

                            @if($value->leave_status == '0')

                            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"In-Progress"}}

                            @elseif($value->leave_status == '1')

                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"Approved"}}

                            @elseif($value->leave_status == '2')

                            <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"Rejected"}}

                            @endif

                          <span class="caret"></span></button>

                          <ul class="dropdown-menu">

                            @if($value->leave_status == '0')
                            <li><a href='javascript:void(0)' class="approvalStatus" data-leavestatus="1" data-userid="{{$value->user_id}}" data-priority="{{$value->priority}}" data-statusname="Approved" data-appliedleaveapprovalid="{{$value->id}}">Approve</a></li>
                            <li><a href='javascript:void(0)' class="approvalStatus" data-leavestatus="2" data-userid="{{$value->user_id}}" data-priority="{{$value->priority}}" data-statusname="Rejected" data-appliedleaveapprovalid="{{$value->id}}">Reject</a></li>
                            @endif
                            @if($value->leave_status == '2')
                            <li><a href='javascript:void(0)' class="approvalStatus" data-leavestatus="1" data-userid="{{$value->user_id}}" data-priority="{{$value->priority}}" data-statusname="Approved" data-appliedleaveapprovalid="{{$value->id}}">Approve</a></li>

                            @endif
                            @if($value->leave_status == 1)
                              @if(checkLastAuthorityForUser($value->user_id, Auth::id()) || Auth::id() == 1)
                                <li><a href='javascript:void(0)' class="approvalStatus" data-leavestatus="2" data-userid="{{$value->user_id}}" data-priority="{{$value->priority}}" data-statusname="Rejected" data-appliedleaveapprovalid="{{$value->id}}">Reject</a></li>
                              @endif
                            @endif
                            <!-- 
  Commented as purpose not understood 
                            @if(($value->applied_leave_id == 14) && date("Ym",strtotime($value->applied_leave_created_at)) == $currentYearMonth)

                              <li><a href='javascript:void(0)' class="approvalStatus" data-leavestatus="2" data-userid="{{$value->user_id}}" data-priority="{{$value->priority}}" data-statusname="Rejected" data-appliedleaveapprovalid="{{$value->id}}">Reject</a></li>

                            @elseif($value->applied_leave_id != 14 && $value->leave_status != '2')

                              <li><a href='javascript:void(0)' class="approvalStatus" data-leavestatus="2" data-userid="{{$value->user_id}}" data-priority="{{$value->priority}}" data-statusname="Rejected" data-appliedleaveapprovalid="{{$value->id}}">Reject</a></li>

                            @endif -->

                          </ul>
                        </div>

                  </td>

                </tr>



                @endforeach



                </tbody>



                <tfoot class="table-heading-style">



                <tr>

                  <th>S.No.</th>

                  <th>Applied At</th>

                  <th>Applied By</th>

                  <th>Leave Type</th>

                  <th>From Date</th>

                  <th>To Date</th>

                  <th>No Of Days</th>

                  <th>Final Status</th>

                  <th>Messages</th>

                  <th>Action</th>

                </tr>



                </tfoot>



              </table>

            </div>

          </div>

        </div>

      </div>

      <!-- /.row -->

      <!-- Main row -->





    </section>

    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->



  <div class="modal fade" id="chatModal">

    <div class="modal-dialog">

      <div class="modal-content">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span></button>

          <h4 class="modal-title">Messages List</h4>

        </div>

        <div class="modal-body chatModalBody">



        </div>



      </div>

      <!-- /.modal-content -->

    </div>

  <!-- /.modal-dialog -->

  </div>

  <!-- /.modal -->



  <div class="modal fade" id="additionalLeaveDetails">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Additional Details</h4>

          </div>

          <div class="modal-body additionalLeaveDetailsBody">



          </div>



        </div>

        <!-- /.modal-content -->

      </div>

    <!-- /.modal-dialog -->

    </div>

    <!-- /.modal -->



  <div class="modal fade" id="dashboardDetails">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title"></h4>

          </div>

          <div class="modal-body dashboardDetailsBody">



          </div>



        </div>

        <!-- /.modal-content -->

      </div>

    <!-- /.modal-dialog -->

    </div>

    <!-- /.modal -->



  <div class="modal fade" id="leaveStatusModal">

        <div class="modal-dialog">

          <div class="modal-content">

            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                <span aria-hidden="true">&times;</span></button>

              <h4 class="modal-title">Leave Status Form</h4>

            </div>

            <div class="modal-body">

              <form id="leaveStatusForm" action="{{ route('leaves.saveAppliedLeaveApproval') }}" method="POST">

                {{ csrf_field() }}

                  <div class="box-body">



                    <div class="form-group">

                      <label for="statusName" class="docType">Selected Status</label>

                      <input type="text" class="form-control" id="statusName" name="statusName" value="" readonly>

                    </div>



                    <input type="hidden" name="leaveStatus" id="leaveStatus">

                    <input type="hidden" name="userId" id="userId">

                    <input type="hidden" name="appliedLeaveApprovalId" id="appliedLeaveApprovalId">



                    <div class="form-group">

                      <label for="comment">Comment</label>

                       <textarea class="form-control" rows="5" name="comment" id="comment"></textarea>

                    </div>



                    <div class="confirm-leave-approval">

                    	<div class="form-group confirmWeekoffDiv">

	                      <input type="checkbox" name="confirmWeekoff" id="confirmWeekoff">

	                      <label for="confirmWeekoff">Please confirm that you have seen the weekoffs of leave applier.</label>

	                    </div>

                    </div>

                  </div>

                  <!-- /.box-body -->

                  <br>



                  <div class="box-footer">

                    <button type="submit" class="btn btn-primary" id="leaveStatusFormSubmit">Submit</button>

                  </div>

            </form>

            </div>



          </div>

          <!-- /.modal-content -->

        </div>

      <!-- /.modal-dialog -->

      </div>

        <!-- /.modal -->



  <script type="text/javascript">



    $("#leaveStatusForm").validate({

      rules :{

          "comment" : {

              required : true,

          },

          "confirmWeekoff":{

            required : true

          }

      },

      messages :{

          "comment" : {

              required : 'Please enter a comment.',

          },

          "confirmWeekoff":{

            required : 'Please confirm.'

          }

      }

    });



  </script>



  <script type="text/javascript">



    $(".approvalStatus").on('click',function(){

      var leaveStatus = $(this).data("leavestatus");

      var userId = $(this).data("userid");

      var statusName = $(this).data("statusname");

      var appliedLeaveApprovalId = $(this).data("appliedleaveapprovalid");

      var priority = $(this).data("priority");



      if(leaveStatus != 1 || priority != 1){

        $(".confirmWeekoffDiv").hide();



      }else if(leaveStatus == 1 && priority == 1){

        $(".confirmWeekoffDiv").show();



      }



      $("#leaveStatus").val(leaveStatus);

      $("#userId").val(userId);

      $("#appliedLeaveApprovalId").val(appliedLeaveApprovalId);

      $("#statusName").val(statusName);



      $('#leaveStatusModal').modal('show');

    });



    $(".chatModal").on('click',function(){

      var appliedLeaveId = $(this).data("appliedleaveid");



      $.ajax({

        type: 'POST',

        url: "{{ route('leaves.appliedLeaveApprovalMessages') }}",

        data: {appliedLeaveId: appliedLeaveId},

        success: function (result) {

          $(".chatModalBody").html(result);

          $('#chatModal').modal('show');

        }

      });

    });



    $(".additionalAppliedLeaveDetails").on('click',function(){

      var appliedLeaveId = $(this).data("appliedleaveid");

      $.ajax({

        type: 'POST',

        url: "{{ route('leaves.additionalAppliedLeaveDetails') }}",

        data: {appliedLeaveId: appliedLeaveId},

        success: function (result) {

          $(".additionalLeaveDetailsBody").html(result);

          $('#additionalLeaveDetails').modal('show');

        }

      });

    });



    $(".dashboardDetails").on('click',function(){

      var userId = $(this).data("userid");

      $.ajax({

        type: 'POST',

        url: "{{ route('employees.othersDashboardDetails') }}",

        data: {userId: userId},

        success: function (result) {

          $(".dashboardDetailsBody").html(result.contents);

          $("#dashboardDetails .modal-title").text(result.title);

          $('#dashboardDetails').modal('show');

        }

      });

    });



    $('#listAppliedLeaveApprovals').DataTable({

      scrollX: true,

      responsive: true

    });

  </script>



  @endsection
