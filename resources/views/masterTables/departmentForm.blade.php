@extends('admins.layouts.app')







@section('content')



<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper content-aside">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1 class="text-center">@if($data['action'] == 'add'){{'Add'}}@else{{'Edit'}}@endif Department</h1>

      <ol class="breadcrumb">

        <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Home</li> -->

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

              <form action="{{route('masterTables.saveDepartment')}}" method="POST" id="departmentForm">

                {{ csrf_field() }}

                  <div class="box-body">

                    <div class="form-group">

                        <label for="departmentEnglishName">@lang('departmentForm.hi.departmentEnglish') / @lang('departmentForm.en.departmentEnglish')</label>

                        <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style text-capitalize" id="departmentEnglishName" name="departmentName" placeholder="Please enter your department" value="@if(@$data['action'] == 'edit'){{@$data['department']->name}}@endif">

                    </div>



                    <input type="hidden" name="action" value="{{$data['action']}}">



                    @if(@$data['department'])

                      <input type="hidden" name="departmentId" value="{{@$data['department']->id}}">                  

                    @endif

                  </div>

                  <!-- /.box-body -->

                  <div class="box-footer">

                      <button type="submit" class="btn btn-primary">@lang('departmentForm.hi.submit') / @lang('departmentForm.en.submit')</button>

                      <a href="{{route('masterTables.listDepartments')}}" class="btn btn-default">@lang('departmentForm.hi.cancel') / @lang('departmentForm.en.cancel')</a>

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

    $("#departmentForm").validate({

      rules: {

        "departmentName" : {

          required: true,

          minlength : 2,

          maxlength: 30

        }

      },

      messages: {

        "departmentName" : {

            required : "Please enter department name.",

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