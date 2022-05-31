@extends('admins.layouts.app')



@section('content')



<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper content-aside">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Applied leaves List

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

            @if(session()->has('cannotCancelError'))

              <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-2">

                <h4 class="login-error-list">Error</h4>

                  <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>

                  {{ session()->get('cannotCancelError') }}

              </div>  

            @endif

            <div class="box-body">

                  <div class="col-md-12 all-progress">

                      <h5 class="all-progress-heading">Progress Bar Status: </h5>

                      <img src="{{asset('public/admin_assets/static_assets/circle_mustard.png')}}" alt="circle-error" class="all-circle-error">

                      <span class="all-span-leave">In Progress</span>

                      <img src="{{asset('public/admin_assets/static_assets/circle_ green.png')}}" alt="circle-error" class="all-circle-error">

                      <span class="all-span-leave">Approved</span>

                      <img src="{{asset('public/admin_assets/static_assets/circle_red.png')}}" alt="circle-error" class="all-circle-error">

                      <span>Rejected</span>

                  </div>

              <table id="listCompensatoryLeaveApprovals" class="table table-bordered table-striped">

                <thead class="table-heading-style">

                  <tr>

                    <th>S.No.</th>

                    <th>Applied At</th>

                    <th>Leave Type</th>

                    <th>From Date</th>

                    <th>To Date</th>
                    <th>No of Days</th>

                    <th>Final Status</th>
                    <th>Progress</th>
                    <th>Remarks</th>

                    <th>Action</th>

                    

                  </tr>

                </thead>



                <tbody>

                  @php $counter = 0;  @endphp
                  @foreach($data as $key => $value)

                	<tr>

                		<td>{{++$counter}}</td>

                    <td>{{date("d/m/Y h:i A",strtotime($value->created_at))}}</td>

                		<td><a href="javascript:void(0)" class="additionalAppliedLeaveDetails" data-appliedleaveid="{{$value->id}}" title="more details">{{$value->leave_type_name}}</a></td>

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
                    <td class="progress-td">

                      @foreach($value->priorityWiseStatus as $key2 => $value2)

                        <div class="progress-manager">

                          <hr class="progress-line">

                          <span class="@if($value2->leave_status == '0'){{'none-dot'}}@elseif($value2->leave_status == '1'){{'approved-dot'}}@elseif($value2->leave_status == '2'){{'rejected-dot'}}@endif"></span>

                          <h6 class="progress-line-type">@if($value2->priority == '1'){{'Supervisor'}}@elseif($value2->priority == '2'){{'Dy.HOD'}}@elseif($value2->priority == '3'){{'HOD'}}@elseif($value2->priority == '4'){{'DGM'}}@elseif($value2->priority == '5'){{'GM'}}@endif</h6>

                        </div>

                      @endforeach

                    </td>
                    <td><span class="chatModal" data-appliedleaveid="{{$value->id}}"><a href="javascript:void(0)"><i class="fa fa-envelope fa-2x"></i></a></span></td>

                    <td>

                      @if($value->canCancelLeave)

                        <span class="label label-default">

                        <a href='{{ url("leaves/cancelAppliedLeave/$value->id") }}'><span class="label label-danger bg-maroon cancelAppliedLeave">Cancel</a></span>

                      @else

                        <span class="label label-default">None</span>

                      @endif  

                    </td>

                    

                	</tr>

                  @endforeach



                </tbody>



                <tfoot class="table-heading-style">

                  <tr>

                    <th>S.No.</th>

                    <th>Applied At</th>

                    <th>Leave Type</th>

                    <th>From Date</th>

                    <th>To Date</th>
                    <th>No of Days</th>

                    <th>Final Status</th>
                    <th>Progress</th>
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



    $(".cancelAppliedLeave").on('click', function(){

      if (!confirm("Are you sure you want to cancel this applied leave?")) {

        return false; 

      }

    });



  	$('#listCompensatoryLeaveApprovals').DataTable({

      scrollX: true,

      responsive: true

    });

  </script>



@endsection