@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">@if($data['action'] == 'add'){{'Add'}}@else{{'Edit'}}@endif Unit</h1>
      <ol class="breadcrumb">
        <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li> -->
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              @if($errors->any())
                <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-1">

                  <button type="button" class="close login-alert-close" data-dismiss="alert" aria-hidden="true">×</button>

                    <h4 class="login-error-list">Error</h4>
                    <ul class="login-alert2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>
              @endif
              <form action="{{route('masterTables.saveUnit')}}" method="POST" id="unitForm">
                {{ csrf_field() }}
                  <div class="box-body">
                    <div class="form-group">
                        <label for="unitName">@lang('unitForm.hi.unitName') / @lang('unitForm.en.unitName')</label>
                        <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style text-capitalize" id="unitName" name="unitName" placeholder="Please enter your unit name" value="@if(@$data['unit']){{@$data['unit']->name}}@endif">
                    </div>

                    <input type="hidden" name="action" value="{{$data['action']}}">

                    @if(@$data['unit'])
                      <input type="hidden" name="unitId" value="{{$data['unit']->id}}">                    
                    @endif
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary">@lang('unitForm.hi.submit') / @lang('unitForm.en.submit')</button>
                      <a href="{{route('masterTables.listUnits')}}" class="btn btn-default">@lang('unitForm.hi.cancel') / @lang('unitForm.en.cancel')</a>
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
    $("#unitForm").validate({
      rules: {
        "unitName" : {
          required: true,
          minlength : 6,
          maxlength: 40,
          alphanumericWithSpace : true
        }
      },
      messages: {
        "unitName" : {
            required : "Please enter unit name.",
            minlength : 'Minimum 6 characters are allowed.',
            maxlength: 'Maximum 40 characters are allowed.'
        }
      }
    });

    $.validator.addMethod("alphanumericWithSpace", function(value, element) {
    return this.optional(element) || /^[A-Za-z][A-Za-z. \d-,]*$/i.test(value);
    }, "Please enter only alphanumeric value.");
 
  </script>

  @endsection