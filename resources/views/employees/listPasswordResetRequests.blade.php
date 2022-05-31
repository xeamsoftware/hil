@extends('admins.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Password Reset Requests List
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
              <table id="listAppliedLeaveApprovals" class="table table-bordered table-striped">

                <thead class="table-heading-style">

                <tr>
                  <th>S.No.</th>
                  <th>Sent By</th>
                  <th>Action</th>
                </tr>

                </thead>

                <tbody>

                @php 
                  $counter = 0; 
                @endphp  

                @foreach($data as $key => $value)  

                <tr>
                  <td>{{++$counter}}</td>
                  <td><a target="_blank" href='{{url("/employees/profile")."/".@$value->user->id}}'>{{@$value->user->first_name}} {{@$value->user->middle_name}} {{@$value->user->last_name}}</a> ({{@$value->user->employee_code}})</td>
                  <td>
                    @if($value->action == '0')
                      <a href='{{url("/employees/passwordResetRequests")."/".@$value->id}}' class="btn bg-navy">Reset Password</a>
                    @else
                      <span class="label label-success">Done</span>
                    @endif  
                  </td>
                </tr>

                @endforeach

                </tbody>

                <tfoot class="table-heading-style">

                <tr>
                  <th>S.No.</th>
                  <th>Sent By</th>
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