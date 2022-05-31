@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Holidays List
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
                            <div class="dropdown">
                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{$sessionName}}
                                    <span class="caret"></span></button>

                                <ul class="dropdown-menu">

                                    @foreach($sessions as $key => $value)
                                        <li><a href='{{url("leaves/holidays/$value->id")}}'>{{$value->name}}</a></li>
                                    @endforeach

                                </ul>

                            </div>
                            <table id="listHolidays" class="table table-bordered table-striped">

                                <thead class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Session</th>
                                    <th>Description</th>
                                </tr>

                                </thead>

                                <tbody>

                                @php $counter = 0; @endphp

                                @foreach($holidays as $holiday)

                                    <tr>
                                        <td>{{++$counter}}</td>
                                        <td>{{$holiday->name}}</td>
                                        <td>{{ $holiday->holiday_type }}</td>
                                        <td>{{date("d/m/Y",strtotime($holiday->from_date))}}</td>
                                        <td>{{date("d/m/Y",strtotime($holiday->to_date))}}</td>
                                        <td>{{$holiday->session->name}}</td>
                                        <td>{{$holiday->description}}</td>
                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Session</th>
                                    <th>Description</th>
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

    <script type="text/javascript">

        $('#listHolidays').DataTable({
            scrollX: true,
            responsive: true
        });
    </script>

@endsection
