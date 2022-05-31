@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Call for Extra Duty Verification List
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
                        <div class="box-body">
                            <table id="listCofExtraDutyLeaveApprovals" class="table table-bordered table-striped">

                                <thead class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>Added By</th>
                                    <th>On Date</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Description</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>

                                </thead>

                                <tbody>
                                @foreach($callOfExtraDutyApprovals as $callOfExtraDutyLeaveApproval)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $callOfExtraDutyLeaveApproval->user->first_name}} {{$callOfExtraDutyLeaveApproval->user->middle_name}} {{$callOfExtraDutyLeaveApproval->user->last_name}}</td>
                                        <td>{{date("d/m/Y",strtotime($callOfExtraDutyLeaveApproval->on_date))}}</td>
                                        <td>{{@$callOfExtraDutyLeaveApproval->extraDutyLeave->in_time}} - {{@$callOfExtraDutyLeaveApproval->extraDutyLeave->out_time}}</td>
                                        <td>{{$callOfExtraDutyLeaveApproval->extraDutyLeave->number_of_hours}}</td>
                                        <td title="{{$callOfExtraDutyLeaveApproval->extraDutyLeave->description}}">
                                            @if(strlen($callOfExtraDutyLeaveApproval->extraDutyLeave->description) < 20)
                                                {{$callOfExtraDutyLeaveApproval->extraDutyLeave->description}}
                                            @else
                                                {{substr($callOfExtraDutyLeaveApproval->extraDutyLeave->description,0,19)}}...
                                            @endif
                                        </td>
                                        <td>
                                            <span class="chatModal" data-callofextradutyleaveid="{{$callOfExtraDutyLeaveApproval->id}}"><a href="javascript:void(0)"><i class="fa fa-envelope fa-2x"></i></a></span>
                                        </td>
                                        <td>
                                            @if($callOfExtraDutyLeaveApproval->leave_status != '1')
                                                <div class="dropdown">
                                                    @if($callOfExtraDutyLeaveApproval->leave_status == '0')
                                                        <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                                            {{"In-Progress"}}
                                                            @elseif($callOfExtraDutyLeaveApproval->leave_status == '2')
                                                                <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">
                                                                    {{"Rejected"}}
                                                                    @endif
                                                                    <span class="caret"></span></button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href='javascript:void(0)' class="approvalStatus" data-statusname="Verified"  data-leavestatus="1" data-approvalid="{{$callOfExtraDutyLeaveApproval->id}}">Verify</a></li>

                                                                    <li><a href='javascript:void(0)' class="approvalStatus" data-statusname="Rejected" data-leavestatus="2" data-approvalid="{{$callOfExtraDutyLeaveApproval->id}}">Reject</a></li>
                                                                </ul>
                                                        </button>
                                                </div>
                                            @else
                                                <span class="label label-success">Verified</span>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>Added By</th>
                                    <th>On Date</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Description</th>
                                    <th>Remarks</th>
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
                    <form id="leaveStatusForm" action="{{ route('leaves.callOfExtraDuty.save.approval') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="box-body">

                            <div class="form-group">
                                <label for="statusName" class="docType">Selected Status</label>
                                <input type="text" class="form-control" id="statusName" name="statusName" value="" readonly>
                            </div>

                            <input type="hidden" name="leaveStatus" id="leaveStatus">
                            <input type="hidden" name="callOfExtraDutyApprovalId" id="callOfExtraDutyApprovalId">

                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea class="form-control" rows="5" name="comment" id="comment"></textarea>
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

    <script type="text/javascript">

        $("#leaveStatusForm").validate({
            rules :{
                "comment" : {
                    required : true,
                }
            },
            messages :{
                "comment" : {
                    required : 'Please enter a comment.',
                }
            }
        });

    </script>

    <script type="text/javascript">

        $(".chatModal").on('click',function(){
            var callOfExtraDutyLeaveId = $(this).data("callofextradutyleaveid");

            $.ajax({
                type: 'POST',
                url: "{{ route('leaves.callOfExtraDuty.verificationMessages') }}",
                data: {callOfExtraDutyLeaveId: callOfExtraDutyLeaveId,  "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    $(".chatModalBody").html(result);
                    $('#chatModal').modal('show');
                }
            });
        });

        $(".approvalStatus").on('click',function(){
            var leaveStatus = $(this).data('leavestatus');
            var callOfExtraDutyApprovalId = $(this).data('approvalid');
            console.log(callOfExtraDutyApprovalId);
            var statusName = $(this).data('statusname');

            if(leaveStatus == 1){
                var result = confirm("Are you sure you want to verify this approval?");
            }else{
                var result = confirm("Are you sure you want to reject this approval?");
            }

            $("#statusName").val(statusName);
            $("#leaveStatus").val(leaveStatus);
            $("#callOfExtraDutyApprovalId").val(callOfExtraDutyApprovalId);

            $("#leaveStatusModal").modal('show');

            // if(result){
            //     if(leaveStatus == 1){
            //       window.location.href = '{{url("leaves/compensatoryLeaveApprovals/verify")}}'+'/'+compensatoryLeaveApprovalId;
            //     }else{
            //       window.location.href = '{{url("leaves/compensatoryLeaveApprovals/reject")}}'+'/'+compensatoryLeaveApprovalId;
            //     }
            // }else{
            //   return false;
            // }
        });

        $('#listCofExtraDutyLeaveApprovals').DataTable({
            scrollX: true,
            responsive: true
        });
    </script>

@endsection
