@extends('admins.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        MasterTables List
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
              <table id="listMasterTables" class="table table-bordered table-striped">

                <thead class="table-heading-style">

                <tr>

                  <th>S.No.</th>
                  <th>Table</th>

                </tr>

                </thead>

                <tbody>

                @php $counter = 0; @endphp  

                @foreach($data as $key => $value)  

                <tr>
                  <td>{{++$counter}}</td>
                  <td><a href='{{ url("masterTables/$value") }}'>{{$key}}</a></td>
                </tr>

                @endforeach

                </tbody>

                <tfoot class="table-heading-style">

                <tr>
                  <th>S.No.</th>
                  <th>Table</th>
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
    
    $('#listMasterTables').DataTable({
      scrollX: true,
      responsive: true
    });
  </script>

  @endsection