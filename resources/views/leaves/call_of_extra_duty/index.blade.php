@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Call for Extra Duty Leaves List
            </h1>
            <a href="{{ route('leaves.callOfExtraDuty.create') }}" class="btn btn-primary add-to-table">
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
                            <table id="listCallOfExtarDutyLeaves" class="table table-bordered table-striped">

                                <thead class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>On Date</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Description</th>
                                    <th>Final Status</th>
                                    <th>Remarks</th>
                                </tr>

                                </thead>

                                <tbody>
                                @foreach($callOfExtraDutyLeaves as $callOfExtraDuty)

                                    <tr>
                                        <td>{{ $loop->iteration  }}</td>
                                        <td>{{ date("d/m/Y",strtotime($callOfExtraDuty->on_date)) }}</td>
                                        <td>{{ @$callOfExtraDuty->in_time }} - {{ @$callOfExtraDuty->out_time }}</td>
                                        <td>{{ $callOfExtraDuty->number_of_hours }}</td>
                                        <td title="{{ $callOfExtraDuty->description }}">
                                            @if(strlen($callOfExtraDuty->description) < 20)
                                                {{$callOfExtraDuty->description}}
                                            @else
                                                {{substr($callOfExtraDuty->description,0,19)}}...
                                            @endif
                                        </td>
                                        <td>
                                            @if($callOfExtraDuty->final_status == '0')
                                                <span class="label label-danger">Unverified</span>
                                            @else
                                                <span class="label label-success">Verified</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="chatModal" data-compensatoryleaveid="{{ $callOfExtraDuty->id }}">
                                                <a href="javascript:void(0)"><i class="fa fa-envelope fa-2x"></i></a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>On Date</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Description</th>
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
            var callOfExtraDutyId = $(this).data("compensatoryleaveid");

            $.ajax({
                type: 'POST',
                {{--url: "{{ route('') }}",--}}
                data: {callOfExtraDutyId: callOfExtraDutyId},
                success: function (result) {
                    $(".chatModalBody").html(result);
                    $('#chatModal').modal('show');
                }
            });
        });

        $('#listCallOfExtarDutyLeaves').DataTable({
            scrollX: true,
            responsive: true
        });
    </script>

@endsection
