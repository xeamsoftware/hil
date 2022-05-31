@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Leave Report List
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('leaves.leaveReportForm')}}"> Leave Report Form</a></li>
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
                            <div class="date-section">
                                <span>From <ins>{{date("d/m/Y",strtotime($reportData['fromDate']))}}</ins>&nbsp; to <ins>{{date("d/m/Y",strtotime($reportData['toDate']))}}</ins>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<ins>{{$reportData['unitName']}}</ins></span>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                    @if($leaveStatus == 1)
                                        In-progress
                                    @elseif($leaveStatus == 2)
                                        Approved
                                    @elseif($leaveStatus == 3)
                                        Rejected
                                    @elseif($leaveStatus == 'all' || !isset($leaveStatus))
                                        All
                                    @endif
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu list-group">
                                    <li class="list-group-item {{ $leaveStatus == 'all' || !isset($leaveStatus) ? 'active' : '' }}" >
                                        <a href='{{url("leaves/generateLeaveReport?fromDate=".$reportData['fromDate']."&toDate=".$reportData['toDate']."&unitId=".$reportData['unitName']."&unitId=".$reportData['unitId']."&leaveStatus=all")}}'>All</a>
                                    </li>
                                    <li class="list-group-item {{ $leaveStatus == 1 ? 'active' : '' }}" >
                                        <a href='{{url("leaves/generateLeaveReport?fromDate=".$reportData['fromDate']."&toDate=".$reportData['toDate']."&unitId=".$reportData['unitName']."&unitId=".$reportData['unitId']."&leaveStatus=1")}}'>In-progress</a>
                                    </li>
                                    <li class="list-group-item {{ $leaveStatus == 2 ? 'active' : '' }}">
                                        <a href='{{url("leaves/generateLeaveReport?fromDate=".$reportData['fromDate']."&toDate=".$reportData['toDate']."&unitId=".$reportData['unitName']."&unitId=".$reportData['unitId']."&leaveStatus=2")}}'>Approved</a>
                                    </li>
                                    <li class="list-group-item {{ $leaveStatus == 3 ? 'active' : '' }}">
                                        <a href='{{url("leaves/generateLeaveReport?fromDate=".$reportData['fromDate']."&toDate=".$reportData['toDate']."&unitId=".$reportData['unitName']."&unitId=".$reportData['unitId']."&leaveStatus=3")}}'>Rejected</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <table id="listLeaveReport" class="table table-bordered table-striped">

                            <thead class="table-heading-style">

                            <tr>

                                <th>Application No.</th>

                                <th>Employee Code</th>

                                <th>Employee Name</th>

                                <th>Leave Type</th>

                                <th>From Date</th>

                                <th>To Date</th>

                                <th>No. of Days</th>

                                <th>Purpose</th>

                                <th>Kind</th>

                                <th>Entry Date</th>
                                <th>Sanctioned Date</th>

                                <th>First Sanc.</th>

                                <th>Final Decision</th>

                            </tr>

                            </thead>

                            <tbody>

                            @php
                                $count = 0;
                            @endphp

                            @foreach(@$data as $key => $value)
                                <tr>
                                    <td>{{@$value->id}}</td>

                                    <td>{{@$value->employee_code}}</td>

                                    <td>{{@$value->first_name}} {{@$value->middle_name}} {{@$value->last_name}}</td>

                                    <td>{{@$value->leave_type_name}}</td>

                                    <td>{{date("d/m/Y",strtotime(@$value->from_date))}}</td>

                                    @if($value->leave_type_id == '11')
                                        <td></td>
                                    @else
                                        <td>{{date("d/m/Y",strtotime(@$value->to_date))}}</td>
                                    @endif
                                    <td>{{@$value->number_of_days}}</td>

                                    <td>{{@$value->purpose}}</td>

                                    <td>
                                        @if(@$value->leave_type_id == 10)
                                            {{"Extraordinary"}}
                                        @else
                                            {{"Normal"}}
                                        @endif
                                    </td>

                                    <td>{{date("d/m/Y",strtotime(@$value->created_at))}}</td>
                                    @if(@$value->final_status !='0' || $value->status =='2')
                                        <td>{{date("d/m/Y",strtotime(@$value->updated_at))}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{@$value->supervisor_code}}</td>



                                    @if($value->final_status =='0' && $value->status == '1'  && $value->leave_status == '1' || $value->leave_status == '0')
                                        <td>{{'PENDING'}}</td>
                                    @elseif($value->final_status =='1' && $value->leave_status == '1')
                                        <td>{{'Sanctioned'}}</td>
                                    @elseif($value->final_status == '0' && $value->leave_status == '2' && $value->status == '1' )
                                        <td>{{'REJECTED'}}</td>

                                    @else($value->from_date && $value->to_date)
                                        <td>NOT APPROVED</td>
                                        @endif
                                        </td>

                                </tr>

                            @endforeach

                            </tbody>

                            <tfoot class="table-heading-style">

                            <tr>

                                <th>Application No.</th>

                                <th>Employee Code</th>

                                <th>Employee Name</th>

                                <th>Leave Type</th>

                                <th>From Date</th>

                                <th>To Date</th>

                                <th>No. of Days</th>

                                <th>Purpose</th>

                                <th>Kind</th>

                                <th>Entry Date</th>
                                <th>Sanctioned Date</th>
                                <th>First Sanc.</th>

                                <th>Final Decision</th>

                            </tr>

                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/dataTables/buttons.dataTables.min.css')}}">
    <script src="{{asset('public/admin_assets/plugins/dataTables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/buttons.flash.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/jszip.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/pdfmake.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/vfs_fonts.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/buttons.html5.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/buttons.print.min.js')}}"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#listLeaveReport').DataTable({
                scrollX: true,
                responsive: true,
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

@endsection
