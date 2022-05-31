@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Holidays List
            </h1>
            <a href="{{url('masterTables/holidays/add')}}" class="btn btn-primary add-to-table">
                <i class="fa fa-plus add-icon-plus"></i> Add</a>
            <a href="{{ route('masterTables.import_holiday') }}" class="btn btn-success add-to-table">
                <i class="fa fa-upload add-icon-plus"></i> Import</a>
            <ol class="breadcrumb">
                <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><a href="{{route('masterTables.list')}}">Tables List</a></li>
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
                                    {{$unitName}}
                                    <span class="caret"></span></button>

                                <ul class="dropdown-menu">

                                    @foreach($units as $key => $value)
                                        <li><a href='{{url("masterTables/holidayList/$value->id")}}'>{{$value->name}}</a></li>
                                    @endforeach

                                </ul>
                            </div>
                            <table id="listHolidays" class="table table-bordered table-striped">

                                <thead class="table-heading-style">

                                <tr>

                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Leave Type</th>

                                </tr>

                                </thead>

                                <tbody>

                                @php $counter = 0; @endphp

                                @foreach($holidays as $holiday)

                                    <tr>
                                        <td>{{++$counter}}</td>
                                        <td>{{$holiday->name}}</td>
                                        <td>{{date("d/m/Y",strtotime($holiday->from_date))}}</td>
                                        <td>{{date("d/m/Y",strtotime($holiday->to_date))}}</td>
                                        <td title="{{$holiday->description}}">
                                            @if(strlen($holiday->description) < 30)
                                                {{$holiday->description}}
                                            @else
                                                {{substr($holiday->description,0,30)}}...
                                            @endif
                                        </td>
                                        <td><a class="btn bg-purple" href='{{ url("masterTables/holidays/edit/$holiday->id")}}' title="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                        <td>

                                            <div class="dropdown">

                                                @if($holiday->status == '1')

                                                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">

                                                        {{"Active"}}

                                                        @else

                                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">

                                                                {{"Inactive"}}

                                                                @endif

                                                                <span class="caret"></span></button>

                                                            <ul class="dropdown-menu">

                                                                <li>

                                                                    @if($holiday->status == '1')

                                                                        <a href='{{ url("masterTables/holidays/deactivate/$holiday->id")}}'>De-activate</a>

                                                                    @else

                                                                        <a href='{{ url("masterTables/holidays/activate/$holiday->id")}}'>Activate</a>

                                                                    @endif

                                                                </li>



                                                            </ul>

                                            </div>

                                        </td>
                                        <td>{{ $holiday->holiday_type }}</td>
                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot class="table-heading-style">

                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Leave Type</th>

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
