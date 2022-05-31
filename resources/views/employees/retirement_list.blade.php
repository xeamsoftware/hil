@extends('admins.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper content-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Employees List
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
                            <form method="post" action="{{ route('employees.add.retirement') }}">
                                @csrf
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="user_id">Employee</label>
                                        <select class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="user_id" id="user_id">
                                            <option value="" selected disabled>Please select employees</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ @$employee->first_name }}{{ @$employee->last_name }} ({{ @$employee->employee_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="user_id">Retirement Date</label>
                                        <input type="date" name="retirement_date" placeholder="Enter Retirement Date" class="form-control datepicker" data-date-format="mm/dd/yyyy">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label></label>
                                        <input type="submit"  class="form-control btn btn-info" value="submit">
                                    </div>
                                </div>

                                <div class="col-md-2"></div>
                            </div>
                            </form>

                            <table id="listEmployees" class="table table-bordered table-striped">

                                <thead class="table-heading-style">

                                <tr>

                                    <th>S.No.</th>

                                    <th>Employee Code</th>

                                    <th>Name</th>

                                    <th>Unit(s)</th>

                                    <th>Mobile Number (Personal)</th>

                                    <th>Created By</th>

                                    <th>Retirement Date</th>

                                </tr>

                                </thead>

                                <tbody>

                                @php $counter = 0; @endphp

                                @foreach($allUsers as $key =>$user)

                                    <tr>

                                        <td>{{++$counter}}</td>

                                        <td>{{@$user->employee_code}}</td>

                                        <td>{{@$user->first_name}} {{@$user->middle_name}} {{@$user->last_name}}</td>

                                        <td>
                                            @foreach(@$user->userUnits as $unit)
                                                {{@$unit->unit->name}}
                                                @if(!$loop->last),@endif
                                            @endforeach
                                        </td>

                                        <td>{{@$user->personal_mobile_number}}</td>

                                        <td>{{@$user->userProfile->creator->first_name}} {{@$user->userProfile->creator->middle_name}} {{@$user->userProfile->creator->last_name}}</td>

                                        <td>{{ @$user->retirement_date }}</td>

                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot class="table-heading-style">
                                <tr>

                                    <th>S.No.</th>

                                    <th>Employee Code</th>

                                    <th>Name</th>

                                    <th>Unit(s)</th>

                                    <th>Mobile Number (Personal)</th>

                                    <th>Created By</th>

                                    <th>Retirement Date</th>

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

    <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/dataTables/buttons.dataTables.min.css')}}">
    <script src="{{asset('public/admin_assets/plugins/dataTables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/buttons.flash.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/jszip.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/pdfmake.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/vfs_fonts.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/buttons.html5.min.js')}}"></script>
    <script src="{{asset('public/admin_assets/plugins/dataTables/buttons.print.min.js')}}"></script>


    <script type="text/javascript">

        $('#listEmployees').DataTable({
            scrollX: true,
            responsive: true,
            dom: 'lBfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

@endsection
