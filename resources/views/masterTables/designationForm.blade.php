@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">@if($data['action'] == 'add'){{'Add'}}@else{{'Edit'}}@endif Designation</h1>
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
              <form action="{{route('masterTables.saveDesignation')}}" method="POST" id="designationForm">
                {{ csrf_field() }}
                  <div class="box-body">
                    <div class="form-group">
                        <label for="designationName">@lang('designationForm.hi.designationName') / @lang('designationForm.en.designationName')</label>
                        <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style text-capitalize" id="designationName" name="designationName" placeholder="Please enter your designation" value="@if(@$data['designation']){{@$data['designation']->name}}@endif">
                    </div>
                    <input type="hidden" name="action" value="{{$data['action']}}">

                    @if(@$data['designation'])
                      <input type="hidden" name="designationId" value="{{$data['designation']->id}}">                    
                    @endif  
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary">@lang('designationForm.hi.submit') / @lang('designationForm.en.submit')</button>
                      <a href="{{route('masterTables.listDesignations')}}" class="btn btn-default">@lang('designationForm.hi.cancel') / @lang('designationForm.en.cancel')</a>
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
    $("#designationForm").validate({
      rules: {
        "designationName" : {
          required: true,
          minlength : 2,
          maxlength: 30,
          englishOnly: true
        }
      },
      messages: {
        "designationName" : {
            required : "Please enter designation name.",
            minlength : 'Minimum 6 characters are allowed.',
            maxlength: 'Maximum 20 characters are allowed.',
        }
      }
    });

    $.validator.addMethod("englishOnly", function(value, element) {
    return this.optional(element) || /^[a-z-," "]+$/i.test(value);
    }, "Please enter only english alphabets and spaces.");

  </script>

  @endsection