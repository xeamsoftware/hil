<!DOCTYPE html>

<html>

<head>

<title>Forgot Password</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="csrf-token" content="{{ csrf_token() }}"> 

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!-- Bootstrap 3.3.7 -->

<link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">



<!-- Font Awesome -->

<link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/font-awesome/css/font-awesome.min.css')}}">



<link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/customStyle.css')}}">



<!-- jQuery 3 -->

<script src="{{asset('public/admin_assets/bower_components/jquery/dist/jquery.min.js')}}"></script>



<!-- Bootstrap 3.3.7 -->

<script src="{{asset('public/admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>



<script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>



<script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>

</head>

<body style="background-image: url({{asset('public/admin_assets/static_assets/bgHil4.jpg')}}); background-size: cover;">

   <div class="container">

      <div class="row">

          <div class="col-md-6 col-md-offset-3">

            <div class="outer-box">

              <!-- error starts here -->

                @if($errors->any())



                <div class="alert alert-danger alert-dismissible login-alerts">



                  <button type="button" class="close login-alert-close" data-dismiss="alert" aria-hidden="true">×</button>



                    <h4 class="login-error-list">Error</h4>

                    <ul class="login-alert2">



                        @foreach ($errors->all() as $error)



                            <li>{{ $error }}</li>



                        @endforeach



                    </ul>



                </div>



                @elseif(session()->has('errorAttempt'))



                    <div class="alert alert-danger alert-dismissible login-alerts">



                      <h4 class="login-error-list">Error</h4>



                        <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>



                        {{ session()->get('errorAttempt') }}



                  </div>



                @elseif(session()->has('successAttempt'))



                    <div class="alert alert-success alert-dismissible login-alerts">



                      <h4 class="login-error-list">Error</h4>



                        <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>



                        {{ session()->get('successAttempt') }}



                  </div>      



                @endif

                <!-- error ends here -->

              <form action="{{route('employees.forgotPassword')}}" class="login-form-box" id="forgotPasswordForm" method="POST">

                {{ csrf_field() }}

                <h5 class="upperright" name="forgotPasswordTitle" id="forgotPasswordTitle">@lang('forgotPassword.hi.forgotPasswordTitle') / @lang('forgotPassword.en.forgotPasswordTitle')</h5>

                <div class="form-group">

                  <label for="employeeCode">@lang('forgotPassword.hi.employeeCode') / @lang('forgotPassword.en.employeeCode')<span class="required">*</span></label>

                  <input type="text" class="form-control hil-input-style" id="employeeCode" placeholder="Enter Employee Code" name="employeeCode">

                </div>

                <div class="buttonbox-forget">

                  <button type="submit" class="btn btn-default submitbtn-login btn-forget" name="sendOtp" id="sendOtp">@lang('forgotPassword.hi.submit') / @lang('forgotPassword.en.submit')</button>

                  <a href="{{route('loginPage')}}" class="btn btn-default submitbtn-cancel btn-forget" name="cancelOtp" id="cancelOtp">@lang('forgotPassword.hi.cancelOtp') / @lang('forgotPassword.en.cancelOtp')</a>

                </div>



              </form>

            </div>

              

          </div>

      </div>    

    </div>

    <script type="text/javascript">

      $("#forgotPasswordForm").validate({

        rules : {

          "employeeCode" : {

            required : true

          }

        },

        messages : {

          "employeeCode" : {

            required : 'Please enter employee code.'

          }

        }

      });



      $("div.alert-dismissible").fadeOut(4000);



    </script>

</body>

</html>

