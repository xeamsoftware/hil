<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>
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

                @endif
                <!-- error ends here -->

              <form action="" class="login-form-box" id="verifyOtpForm" method="POST">
                {{ csrf_field() }}
                <h5 class="upperright" id="verifyOtpTitle">@lang('verifyOtp.hi.verifyOtpTitle') / @lang('verifyOtp.en.verifyOtpTitle')</h5>
                <input type="hidden" name="passwordReset" value="{{$passwordReset->id}}">
                <div class="form-group">
                  <label for="mobileNumber">@lang('verifyOtp.hi.mobileNumber') / @lang('verifyOtp.en.mobileNumber')</label>
                  <input type="text" class="form-control hil-input-style" id="mobileNumber" value="{{$passwordReset->user->personal_mobile_number}}" name="mobileNumber" readonly>
                </div>
                <div class="form-group">
                  <label for="otp">@lang('verifyOtp.hi.otp') / @lang('verifyOtp.en.otp')<span class="required">*</span></label>
                  <input type="text" class="form-control hil-input-style" id="otp" placeholder="Please enter 6 digit number" name="otp">
                  <span class="otpError"></span>
                </div>
                <div class="buttonbox-forget">
                  <a href="javascript:void(0)" class="resend-otp" id="resendOtp">@lang('verifyOtp.hi.resendOtp') / @lang('verifyOtp.en.resendOtp')</a>
                  <a href="{{route('loginPage')}}" class="btn btn-default submitbtn-cancel btn-forget" name="cancelTheForm" id="cancelTheForm">@lang('verifyOtp.hi.cancelTheForm') / @lang('verifyOtp.en.cancelTheForm')</a>
                </div>
                
              </form>
            </div>
              
          </div>
      </div>    
    </div>
     <script type="text/javascript">

      $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $("#resendOtp").on('click',function(){
        
        var passwordReset = "{{$passwordReset->id}}";
        var mobileNumber = $("#mobileNumber").val();

        $.ajax({
          type: 'POST',
          url: "{{route('employees.resendOtp')}}",
          data: {passwordReset: passwordReset, mobileNumber: mobileNumber},
          success: function(result){
            if(result.error){
              $(".otpError").text(result.message).css("color","#f00");
            }else{
              $(".otpError").text(result.message).css("color","#0f0");
            }
          }
        });
      });

      $("#otp").on("keyup",function(){
          var passwordReset = "{{$passwordReset->id}}";
          var mobileNumber = $("#mobileNumber").val();
          var otp = $(this).val();

          $.ajax({
            type: 'POST',
            url: "{{route('employees.verifyOtp')}}",
            data: {passwordReset: passwordReset, mobileNumber: mobileNumber, otp: otp},
            success: function(result){
              if(result.error){
                $(".otpError").text(result.message).css("color","#f00");
              }else{
                $(".otpError").text(result.message).css("color","#0f0");

                setTimeout(function(){}, 5000);
                window.location.replace("{{ url('/') }}");
              }
            }
          });
      });

      $("div.alert-dismissible").fadeOut(4000);
      
    </script>
</body>
</html>
