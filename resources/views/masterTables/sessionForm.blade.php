@extends('admins.layouts.app')


@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">@if($data['action'] == 'add'){{'Add'}}@else{{'Edit'}}@endif Session</h1>
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
              <form action="{{ route('masterTables.saveSession') }}" method="POST" id="sessionForm">
                {{ csrf_field() }}
                  <div class="box-body">
                    <div class="form-group">
                        <label for="sessionName">@lang('sessionForm.hi.addSession') / @lang('sessionForm.en.addSession')</label>
                        <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" id="sessionName" name="sessionName" placeholder="eg:- 2018-2019" value="@if(@$data['session']){{@$data['session']->name}}@endif">
                    </div>

                    <input type="hidden" name="action" value="{{$data['action']}}">

                    @if(@$data['session'])
                      <input type="hidden" name="sessionId" value="{{$data['session']->id}}">                    
                    @endif
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary">@lang('sessionForm.hi.save') / @lang('sessionForm.en.save')</button>
                      <a href="{{route('masterTables.listSessions')}}" class="btn btn-default">@lang('sessionForm.hi.cancel') / @lang('sessionForm.en.cancel')</a>
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
    $("#sessionForm").validate({
      rules: {
        "sessionName" : {
          required: true,
          minlength : 6,
          maxlength: 30,
          spacespecial: true
        }
      },
      messages: {
        "sessionName" : {
            required : "Please enter session name.",
            minlength : 'Minimum 6 characters are allowed.',
            maxlength: 'Maximum 20 characters are allowed.'
        }
      }
    });

    $.validator.addMethod("spacespecial", function(value, element) {
      return this.optional(element) || /^[0-9-,]+(\s{0,1}[0-9-, ])*$/i.test(value); 
    },"Please enter only digits.");
    
  </script>

  @endsection