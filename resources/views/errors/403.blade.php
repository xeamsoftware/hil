@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper content-aside">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        403 Error Page

      </h1>

      <ol class="breadcrumb">

        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">403 error</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">



      <div class="error-page">

        <h2 class="headline text-red">403</h2>



        <div class="error-content">

          <h3><i class="fa fa-warning text-red"></i> You do not have permission to access this page.</h3>



          <p>

             You may <a href="{{ route('employees.dashboard') }}">return to dashboard.</a> 

          </p>



          

        </div>

      </div>

      <!-- /.error-page -->



    </section>

    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->

  @endsection