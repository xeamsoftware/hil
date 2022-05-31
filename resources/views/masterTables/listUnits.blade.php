@extends('admins.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Units List
      </h1>
      <a href="{{url('masterTables/units/add')}}" class="btn btn-primary add-to-table">
        <i class="fa fa-plus add-icon-plus"></i> Add</a>
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
              <table id="listUnits" class="table table-bordered table-striped">

                <thead class="table-heading-style">

                <tr>

                  <th>S.No.</th>
                  <th>Name</th>
                  <th>Action</th>
                  <th>Status</th>

                </tr>

                </thead>

                <tbody>

                @php $counter = 0; @endphp  

                @foreach($units as $unit)  

                <tr>
                  <td>{{++$counter}}</td>
                  <td>{{$unit->name}}</td>
                  <td><a class="btn bg-purple" href='{{ url("masterTables/units/edit/$unit->id")}}' title="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                  <td>

                        <div class="dropdown">

                            @if($unit->status == '1')

                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"Active"}}

                            @else

                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">

                             {{"Inactive"}}

                            @endif  

                          <span class="caret"></span></button>

                          <ul class="dropdown-menu">

                            <li>

                                @if($unit->status == '1')

                                  <a href='{{ url("masterTables/units/deactivate/$unit->id")}}'>De-activate</a>

                                @else

                                  <a href='{{ url("masterTables/units/activate/$unit->id")}}'>Activate</a>

                                @endif

                            </li>

                            

                          </ul>

                        </div>

                  </td>
                </tr>

                @endforeach

                </tbody>

                <tfoot class="table-heading-style">

                <tr>
                  <th>S.No.</th>
                  <th>Name</th>
                  <th>Action</th>
                  <th>Status</th>

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
    
    $('#listUnits').DataTable({
      scrollX: true,
      responsive: true
    });
  </script>

  @endsection