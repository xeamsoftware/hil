@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">@lang('changePassword.hi.changePasswordTitle') / @lang('changePassword.en.changePasswordTitle')</h1>
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

              @elseif(session()->has('errorAttempt')) 
              
                <div class="alert alert-danger alert-dismissible login-alerts-change-pswrd-1">

                  <h4 class="login-error-list">Error</h4>

                    <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>

                    {{ session()->get('errorAttempt') }}

                </div> 
              @endif  
	            <!-- form start -->
	            <form action="{{route('employees.saveChangePassword')}}" method="POST" id="changePasswordForm">
                {{ csrf_field() }}
	              <div class="box-body">
	                <div class="form-group">
	                  <label for="oldPassword">@lang('changePassword.hi.oldPassword') / @lang('changePassword.en.oldPassword')</label>
	                  <input type="password" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="oldPassword" id="oldPassword" placeholder="Enter old password">
	                </div>
	                <div class="form-group">
	                  <label for="newPassword">@lang('changePassword.hi.newPassword') / @lang('changePassword.en.newPassword')</label>
	                  <input type="password" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" name="newPassword" id="newPassword" placeholder="Enter new password">
	                </div>
	                <div class="form-group">
	                  <label for="confirmPassword">@lang('changePassword.hi.confirmPassword') / @lang('changePassword.en.confirmPassword')</label>
	                  <input type="password" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" placeholder="Enter new password again" name="confirmPassword" id="confirmPassword">
	                </div>

	              </div>
	              <!-- /.box-body -->

	              <div class="box-footer">
	                <button type="submit" class="btn btn-primary">@lang('changePassword.hi.submit') / @lang('changePassword.en.submit')</button>
	                <a href="{{route('employees.myProfile')}}" class="btn btn-default">@lang('changePassword.hi.cancel') / @lang('changePassword.en.cancel')</a>
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
    $("#changePasswordForm").validate({
      rules : {
          "oldPassword" : {
              required : true,
              minlength : 6,
              maxlength: 20
          },
          "newPassword" : {
              required : true,
              minlength : 6,
              maxlength: 20
          },
          "confirmPassword" : {
              required : true,
              minlength : 6,
              maxlength: 20,
              equalTo: "#newPassword"
          }
      },
      messages : {
          "oldPassword" : {
              required : "Please enter your old password.",
              minlength : 'Minimum 6 characters are allowed.',
              maxlength: 'Maximum 20 characters are allowed.'
          },
          "newPassword" : {
              required : 'Please enter your new password.',
              minlength : 'Minimum 6 characters are allowed.',
              maxlength: 'Maximum 20 characters are allowed.'
          },
          "confirmPassword" : {
              required : 'Please confirm your new password.',
              minlength : 'Minimum 6 characters are allowed.',
              maxlength: 'Maximum 20 characters are allowed.',
              equalTo: 'Confirmed password does not match the New password.'
          }
      }
    });
  </script>

  @endsection