@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Import Leave Accumulations Form</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
      	<div class="col-md-12">
      		<div class="box box-primary">
            @if(session()->has('accumulationFileError'))
              <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-2">
                <h4 class="login-error-list">Error</h4>
                  <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ session()->get('accumulationFileError') }}
              </div>  
            @endif
                
              <form id="importFileForm" action="{{ route('employees.importAccumulationFile') }}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="box-body">
                    <div class="form-group">
                        <label for="accumulationFile">Choose File</label>
                        <input type="file" name="accumulationFile" id="accumulationFile">
                    </div>
                  </div>
                  <!-- /.box-body -->

                <div class="box-footer">
                      <button type="Submit" class="btn btn-primary submit-btn-style">Submit</button>
                </div>

              </form>
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
    $("#importFileForm").validate({
      rules : {
        "accumulationFile" : {
          required : true,
          extension: "xls|xlsx"
        }
      },
      messages : {
        "accumulationFile" : {
          required : "Please select a file.",
          extension : "Please upload a .xls or .xlsx file only."
        }
      }
    });
  </script>

  @endsection