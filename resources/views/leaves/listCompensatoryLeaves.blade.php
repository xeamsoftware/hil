@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Compensatory Leaves List
            </h1>
            <a href="{{url('leaves/compensatoryLeaves/add')}}" class="btn btn-primary add-to-table">
                <i class="fa fa-plus add-icon-plus"></i> Add</a>
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
                            <table id="listCompensatoryLeaves" class="table table-bordered table-striped">

                                <thead class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    {{--                  <th>Leave Type</th>--}}
                                    <th>On Date</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Description</th>
                                    <th>Document</th>
                                    <th>Final Status</th>
                                    <th>Remarks</th>
                                </tr>

                                </thead>

                                <tbody>

                                @php $counter = 0; @endphp


                                @foreach($compensatoryLeaves as $compensatoryLeave)

                                    <tr>
                                        <td>{{++$counter}}</td>
                                        {{--                  <td>{{$compensatoryLeave->leave_type}}</td>--}}
                                        <td>{{date("d/m/Y",strtotime($compensatoryLeave->on_date))}}</td>
                                        <td>{{@$compensatoryLeave->in_time}} - {{@$compensatoryLeave->out_time}}</td>
                                        <td>{{$compensatoryLeave->number_of_hours}}</td>
                                        <td title="{{$compensatoryLeave->description}}">
                                            @if(strlen($compensatoryLeave->description) < 20)
                                                {{$compensatoryLeave->description}}
                                            @else
                                                {{substr($compensatoryLeave->description,0,19)}}...
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($compensatoryLeave->document_name))
                                                <span >
                                                <a href="{{ route('leaves.downloadLeaveDocuments',$compensatoryLeave->document_name ) }}"><i class="fa fa-download" style="font-size:20px"></i></a>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($compensatoryLeave->final_status == '0')
                                                <span class="label label-danger">Unverified</span>
                                            @else
                                                <span class="label label-success">Verified</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="chatModal" data-compensatoryleaveid="{{$compensatoryLeave->id}}"><a href="javascript:void(0)"><i class="fa fa-envelope fa-2x"></i></a></span>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    {{--                  <th>Leave Type</th>--}}
                                    <th>On Date</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Description</th>
                                    <th>Document</th>
                                    <th>Final Status</th>
                                    <th>Remarks</th>
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
                    <h4 class="modal-title">Remarks List</h4>
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
            var compensatoryLeaveId = $(this).data("compensatoryleaveid");

            $.ajax({
                type: 'POST',
                url: "{{ route('leaves.compensatoryLeaveVerificationMessages') }}",
                data: {compensatoryLeaveId: compensatoryLeaveId},
                success: function (result) {
                    $(".chatModalBody").html(result);
                    $('#chatModal').modal('show');
                }
            });
        });

        $('#listCompensatoryLeaves').DataTable({
            scrollX: true,
            responsive: true
        });
    </script>

@endsection
