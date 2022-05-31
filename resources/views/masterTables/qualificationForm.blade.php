@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center">@if($data['action'] == 'add'){{'Add'}}@else{{'Edit'}}@endif Qualification</h1>
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
              <form action="{{route('masterTables.saveQualification')}}" method="POST" id="qualificationForm">
                {{ csrf_field() }}
                  <div class="box-body">
                    <div class="form-group">
                        <label for="qualificationEnglishName">@lang('qualificationForm.hi.qualificationEnglish') / @lang('qualificationForm.en.qualificationEnglish')</label>
                        <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style text-capitalize" id="qualificationEnglishName" name="qualificationEnglishName" placeholder="Please enter your qualification" value="@if(@$data['action'] == 'edit'){{@$data['qualification']->name}}@endif">
                    </div>

                    <div class="form-group">
                        <label for="qualificationHindiName">@lang('qualificationForm.hi.qualificationHindi') / @lang('qualificationForm.en.qualificationHindi')</label>
                        <input type="text" class="form-control single-input-lbl input-sm only-dropdown-input basic-detail-input-style" id="qualificationHindiName" name="qualificationHindiName" placeholder="Please enter your qualification" value="@if(@$data['action'] == 'edit'){{@$data['qualification']->hindi_name}}@endif">
                    </div>

                    <input type="hidden" name="action" value="{{$data['action']}}">

                    @if(@$data['qualification'])
                      <input type="hidden" name="qualificationId" value="{{@$data['qualification']->id}}">                    
                    @endif
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary">@lang('qualificationForm.hi.submit') / @lang('qualificationForm.en.submit')</button>
                      <a href="{{route('masterTables.listQualifications')}}" class="btn btn-default">@lang('qualificationForm.hi.cancel') / @lang('qualificationForm.en.cancel')</a>
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
    $("#qualificationForm").validate({
      rules: {
        "qualificationEnglishName" : {
          required: true,
          minlength : 1,
          maxlength: 30,
          englishOnly: true
        },
        "qualificationHindiName" : {
          required: true,
          minlength : 1,
          maxlength: 30,
          hindiOnly: true
        }
      },
      messages: {
        "qualificationEnglishName" : {
            required : "Please enter qualification name.",
            minlength : 'Minimum 6 characters are allowed.',
            maxlength: 'Maximum 20 characters are allowed.',
        },
        "qualificationHindiName" : {
            required : 'Please enter qualification name.',
            minlength : 'Minimum 6 characters are allowed.',
            maxlength: 'Maximum 20 characters are allowed.'
        }
      }
    });

    $.validator.addMethod("englishOnly", function(value, element) {
    return this.optional(element) || /^[a-z-," "]+$/i.test(value);
    }, "Please enter only english alphabets and spaces.");

    $.validator.addMethod("hindiOnly", function(value, element) {
    return this.optional(element) || /^[^a-zA-Z0-9]+$/i.test(value);
    }, "कृपया केवल हिंदी अक्षर और रिक्त स्थान दर्ज करें।");

  </script>

  @endsection