<!DOCTYPE html>

<html>

<head>

<title>Login Page</title>

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

            <!-- outerbox starts here -->

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



                @elseif(session()->has('success'))    



                    <div class="alert alert-success alert-dismissible login-alerts">



                      <h4 class="login-error-list">Success</h4>



                        <button type="button" class="close login-alert-close2" data-dismiss="alert" aria-hidden="true">×</button>



                        {{ session()->get('success') }}



                    </div>



                @endif

                <!-- error ends here -->



              <form action="{{route('employees.login')}}" class="login-form-box" id="loginPage" method="POST">

                {{ csrf_field() }}

                <h5 class="upperright" name="loginForm" id="loginForm">@lang('loginPage.hi.loginForm') / @lang('loginPage.en.loginForm'). </h5>

                <div class="form-group">

                  <label for="employeeCode">@lang('loginPage.hi.employeeCode') / @lang('loginPage.en.employeeCode') :<span class="required">*</span></label>

                  <input type="text" autocomplete="off" class="form-control hil-input-style" id="employeeCode" placeholder="Please enter employee code" name="employeeCode">

                </div>

                <div class="form-group">

                  <label for="password">@lang('loginPage.hi.employeePassword') / @lang('loginPage.en.employeePassword') :<span class="required">*</span></label>

                  <input type="password" autocomplete="off" class="form-control hil-input-style" id="password" placeholder="Enter password" name="password" >

                </div>

                <div class="checkbox">

                  <label><input type="checkbox" name="remember">@lang('loginPage.hi.remember') / @lang('loginPage.en.remember')</label>

                </div>

                <div class="buttonbox">

                  <button type="submit" class="btn btn-default submitbtn-login" name="signIn" id="signIn">@lang('loginPage.hi.signIn') / @lang('loginPage.en.signIn')</button>

                  <a href="{{route('forgotPassword')}}" class="forget-pswrd-login" id="forgotPassword">@lang('loginPage.hi.forgotPassword') / @lang('loginPage.en.forgotPassword')</a>

                </div>

              </form>

            </div>

            <!-- outerbox ends here -->

          </div>

      </div>    

    </div>

    <script type="text/javascript">

      $("#loginPage").validate({

        rules : {

          "employeeCode" : {

            required : true

          },

          "password" : {

             required : true

          }

        },

        messages : {

          "employeeCode" : {

            required : 'Please enter employee code.'

          },

          "password" : {

            required : 'Please enter password.'

          }

        }

      });



      //$("div.alert-dismissible").fadeOut(4000);



    </script>

</body>

</html>

