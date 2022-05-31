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
                <div>
                    <form action="{{ url('employees/list') }}" method="get">
                    <div class="form-group">
                        <select name="unit_id" style="background-color: white">
                            <option value="all" {{ app('request')->input('unit_id')  == 'all' ? 'selected' : '' }}>All</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ app('request')->input('unit_id')  == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        <input type="submit" value="Filter" class="btn btn-info">
                    </div>
                    </form>
                </div>
              <table id="listEmployees" class="table table-bordered table-striped">

                <thead class="table-heading-style">

                <tr>

                  <th>S.No.</th>

                  <th>Employee Code</th>

                  <th>Name</th>

                  <th>Unit(s)</th>

                  <th>Mobile Number (Personal)</th>

                  <th>Created By</th>

                  @can('edit-user')
                  <th>Actions</th>

                  <th>Status</th>
                  @endcan

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

                  @can('edit-user')
                  <td class="edit-list-icon"><a class="btn bg-purple" href='{{ url("employees/edit/$user->id")}}' target="_blank" title="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;<a class="btn btn-primary" target="_blank" href='{{ url("employees/profile/$user->id")}}' title="view"><i class="fa fa-eye" aria-hidden="true"></i></a></td>

                  <td>

                        <div class="dropdown">

                            @if($user->status == '1')

                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"Active"}}

                            @else

                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"Inactive"}}

                            @endif

                          <span class="caret"></span></button>

                          <ul class="dropdown-menu">

                            <li>

                                @if($user->status == '1')

                                  <a href='{{ url("/employees/status/deactivate/$user->id")}}'>De-activate</a>

                                @else

                                  <a href='{{ url("/employees/status/activate/$user->id")}}'>Activate</a>

                                @endif

                            </li>



                          </ul>

                        </div>

                  </td>
                  @endcan

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

                  @can('edit-user')
                  <th>Actions</th>

                  <th>Status</th>
                  @endcan

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
