<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}"> 

  <title>Admin | Panel</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.7 -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/font-awesome/css/font-awesome.min.css')}}">

  <!-- Ionicons -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/Ionicons/css/ionicons.min.css')}}">

  <!-- Theme style -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/AdminLTE.css')}}">

  <link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/customStyle.css')}}">

  <!-- AdminLTE Skins. Choose a skin from the css/skins

       folder instead of downloading all of them to reduce the load. -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/skins/_all-skins.min.css')}}">

 

  <!-- Date Picker -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

  

  <!-- bootstrap wysihtml5 - text editor -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">



  <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.css')}}">



  <!-- Select2 -->

  <link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/select2/dist/css/select2.min.css')}}">



  <!-- jQuery 3 -->

  <script src="{{asset('public/admin_assets/bower_components/jquery/dist/jquery.min.js')}}"></script>

  <script src="{{asset('public/admin_assets/bower_components/moment/min/moment.min.js')}}"></script>



  <!-- Bootstrap 3.3.7 -->

  <script src="{{asset('public/admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>



  <!-- datepicker -->

  <script src="{{asset('public/admin_assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>



  <!-- Bootstrap WYSIHTML5 -->



  <script src="{{asset('public/admin_assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>



  <script type="text/javascript">

      $.ajaxSetup({

        headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

      });

  </script>



  <script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>



  <script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>



  <script src="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.js')}}"></script>



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->



  <!-- Google Font -->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition skin-purple sidebar-mini">

<div class="wrapper">



  <header class="main-header">

    <!-- Logo -->

    @php 

      $user = Auth::user();

      $staticPic = config('constants.static.profilePic');

      $profilePicPath = config('constants.uploadPaths.profilePic');



      if(empty($user->profile_pic)){

        $user->profile_pic = $staticPic;

      }else{

        $user->profile_pic = $profilePicPath.$user->profile_pic;

      }



      $user->unreadNotifications = getMyLimitedNotifications($staticPic,$profilePicPath,$user->id, 10);

      

      $notificationIds = [];

      $unread = 0;

      

    @endphp

    <a href="JavaScript:Void(0);" class="logo">

      <!-- mini logo for sidebar mini 50x50 pixels -->

      <span class="logo-mini"><b></b></span>

      <!-- logo for regular state and mobile devices -->

      <span class="logo-lg"><b></b></span>
      HIL
    </a>

    <!-- Header Navbar: style can be found in header.less -->

    <nav class="navbar navbar-static-top">

      <!-- Sidebar toggle button-->

      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">

        <span class="sr-only">Toggle navigation</span>

      </a>



      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">

          <!-- Messages: style can be found in dropdown.less-->

          <li class="dropdown messages-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <i class="fa fa-envelope-o"></i>

              <span class="label label-success unreadNotificationsCount"></span>

            </a>

            <ul class="dropdown-menu">

              <li class="header">You have <span class="unreadNotificationsCount"></span> message(s)</li>

              <li>

                <!-- inner menu: contains the actual data -->

                <ul class="menu">

                  @if(!$user->unreadNotifications->isEmpty())

                  @foreach($user->unreadNotifications as $key => $value)

                  @php

                      $notificationIds[] = $value->id;



                      if($value->read_status == 0){

                        ++$unread;

                      }

                  @endphp

                  <li><!-- start message -->

                    <a href="javascript:void(0)">

                      <div class="pull-left">

                        <img src="{{$value->profile_pic}}" class="img-circle" alt="User Image">

                      </div>

                      <h5 title="{{$value->first_name}} {{$value->middle_name}} {{$value->last_name}}">

                        {{$value->first_name}} {{$value->middle_name}} {{$value->last_name}}

                        <small><i class="fa fa-clock-o"></i> {{date("d/m/Y H:i:s",strtotime($value->created_at))}}</small>

                      </h5>

                      <p title="{{$value->message}}">

                        @if(strlen($value->message) > 25)

                          {{substr($value->message,0,25)}}...

                        @else

                          {{$value->message}}

                        @endif  

                      </p>

                    </a>

                  </li>

                  <!-- end message -->

                  @endforeach

                  @endif

                  

                </ul>

              </li>

              <li class="footer"><a href="{{route('employees.allMessages')}}">See All Messages</a></li>

            </ul>

          </li>

          <!-- Notifications: style can be found in dropdown.less -->

          <!-- <li class="dropdown notifications-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <i class="fa fa-bell-o"></i>

              <span class="label label-warning">10</span>

            </a>

            <ul class="dropdown-menu">

              <li class="header">You have 10 notifications</li>

              <li> -->

                <!-- inner menu: contains the actual data -->

                <!-- <ul class="menu">

                  <li>

                    <a href="#">

                      <i class="fa fa-users text-aqua"></i> 5 new members joined today

                    </a>

                  </li>

                  <li>

                    <a href="#">

                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the

                      page and may cause design problems

                    </a>

                  </li>

                  <li>

                    <a href="#">

                      <i class="fa fa-users text-red"></i> 5 new members joined

                    </a>

                  </li>

                  <li>

                    <a href="#">

                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made

                    </a>

                  </li>

                  <li>

                    <a href="#">

                      <i class="fa fa-user text-red"></i> You changed your username

                    </a>

                  </li>

                </ul>

              </li>

              <li class="footer"><a href="#">View all</a></li>

            </ul>

          </li> -->

          <!-- Tasks: style can be found in dropdown.less -->

          

          <!-- User Account: style can be found in dropdown.less -->

          <li class="dropdown user user-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <img src="{{$user->profile_pic}}" class="user-image" alt="User Image">

              <span class="hidden-xs">{{$user->first_name}} {{$user->last_name}}</span>

            </a>

            <ul class="dropdown-menu">

              <!-- User image -->

              <li class="user-header">

                <img src="{{$user->profile_pic}}" class="img-circle" alt="User Image">



                <p>

                  {{$user->first_name}} {{$user->last_name}}

                  <!-- <small>Member since Nov. 2012</small> -->

                </p>

              </li>

              <!-- Menu Body -->

              

              <!-- Menu Footer-->

              <li class="user-footer">

                <div class="pull-left">

                  <a href="{{ route('employees.myProfile') }}" class="btn btn-default btn-flat">Profile</a>

                </div>

                <div class="pull-right">

                  <a href="{{ route('employees.logout') }}" class="btn btn-default btn-flat">Sign out</a>

                </div>

              </li>

            </ul>

          </li>

          <!-- Control Sidebar Toggle Button -->

          

        </ul>

      </div>

    </nav>

  </header>



  <script type="text/javascript">

    var unread = "{{$unread}}";

    $(".unreadNotificationsCount").text(unread);

    

    $(".messages-menu").on('click',function(){

      var notificationIds = "<?php echo json_encode($notificationIds); ?>";

      notificationIds = JSON.parse(notificationIds);

      if(notificationIds.length > 0){

        $.ajax({

          type: 'POST',

          url: "{{route('employees.unreadMessages')}}",

          data: {notificationIds: notificationIds},

          success: function(result){

            if(result.status){

              $(".unreadNotificationsCount").text("0");

            }

          }

        });

      }

    });

  </script>