<!DOCTYPE html>
<html>
<head>
<title>Registration Page</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}"> 
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/font-awesome/css/font-awesome.min.css')}}">

<!-- Select2 -->
<link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/select2/dist/css/select2.min.css')}}">



<!-- Theme style -->
<link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/AdminLTE.css')}}">
<link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/customStyle.css')}}">
 <!-- Date Picker -->
  <link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">


<!-- jQuery 3 -->
<script src="{{asset('public/admin_assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('public/admin_assets/bower_components/moment/min/moment.min.js')}}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{asset('public/admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<!-- datepicker -->
<script src="{{asset('public/admin_assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>


<!-- Select2 -->
<script src="{{asset('public/admin_assets/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>

<script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>

</head>
<body>
	<div class="container">
		<div class="body-box">
				<div class="form-heading">
					<span id="hilRegistrationForm">@lang('createUserForm.hi.hilRegistrationForm') / @lang('createUserForm.en.hilRegistrationForm')</span>
				</div>
			<form id="createUserForm" action="" method="">	
			<div class="basic-detail">
				<span class="basicDetail" id="basicDetail" name="basicDetail" >@lang('createUserForm.hi.basicDetail') / @lang('createUserForm.en.basicDetail')</span>
			</div>

			<div class="row basic-details-inputs">
				<div class="col-md-6">
					<div class="form-group">
					  <label for="unitId" class="control-label">@lang('createUserForm.hi.unitId') / @lang('createUserForm.en.unitId')<span class="required">*</span></label>
					  	<select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="unitId"id="unitId">
					  		<option value="" selected disabled>Please Select Employee's unit</option>
						    <option>Sector-5</option>
						    <option>Sector-6</option>
						    <option>Sector-7</option>
						    <option>Sector-8</option>
						  </select>	  
					</div>

					<div class="form-group">
					    <label class="control-label" for="employeeCode">@lang('createUserForm.hi.employeeCode') / @lang('createUserForm.en.employeeCode')<span class="required">*</span></label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="employeeCode" id="employeeCode" placeholder="615">
					</div>

					<div class="form-group">
					    <label class="control-label" for="firstName">@lang('createUserForm.hi.firstName') / @lang('createUserForm.en.firstName')<span class="required">*</span></label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="firstName" id="firstName" placeholder="Enter first name">
					</div>

					<div class="form-group">
					    <label class="control-label" for="middleName">@lang('createUserForm.hi.middleName') / @lang('createUserForm.en.middleName')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="middleName" id="middleName" placeholder="Enter middle name">
					</div>

					<div class="form-group">
					    <label class="control-label" for="lastName">@lang('createUserForm.hi.lastName') / @lang('createUserForm.en.lastName')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="lastName" id="lastName" placeholder="Enter last name">
					</div>
					<div class="form-group">
					    <label class="control-label" for="fatherName">@lang('createUserForm.hi.fatherName') / @lang('createUserForm.en.fatherName')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="fatherName" id="fatherName" placeholder="Enter father's name">
					</div>
					<div class="form-group">
					    <label class="control-label" for="motherName">@lang('createUserForm.hi.motherName') / @lang('createUserForm.en.motherName')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="motherName" id="motherName" placeholder="Enter mother's name">
					</div>

					<div class="form-group">
					  <label for="designationId" class="control-label">@lang('createUserForm.hi.designationId') / @lang('createUserForm.en.designationId')<span class="required">*</span></label>
					  	<select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="designationId" id="designationId">
					  		<option value="" selected disabled>Please Select Employee's Designation</option>
						    <option>Executive</option>
						    <option>Senior</option>
						    <option>Manager</option>
						    <option>Senior Manager</option>
						  </select>  
					</div>
		             

		            <div class="form-group">
					  <label for="qualificationId" class="control-label">@lang('createUserForm.hi.qualificationId') / @lang('createUserForm.en.qualificationId')<span class="required">*</span></label>
					  	<select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="qualificationId" id="qualificationId">
					  		<option value="" selected disabled>Please Select Employee's qualification</option>
						    <option>10th</option>
						    <option>12th</option>
						    <option>BA</option>
						    <option>MA</option>
						  </select>  
					</div>

					<div class="form-group">
					    <label class="control-label" for="dateOfJoining">@lang('createUserForm.hi.dateOfJoining') / @lang('createUserForm.en.dateOfJoining')<span class="required">*</span></label>
					      <div class="input-group date single-input-lbl">
			                  <div class="input-group-addon date-icon input-sm basic-detail-input-style">
			                    <i class="fa fa-calendar"></i>
			                  </div>
		                  	  <input type="text" class="form-control pull-right date-input input-sm basic-detail-input-style" name="dateOfJoining" id="dateOfJoining">
		                  </div>
					</div>

					<div class="form-group">
					    <label class="control-label" for="dateOfBirth">@lang('createUserForm.hi.dateOfBirth') / @lang('createUserForm.en.dateOfBirth')</label>
					      <!-- <input type="text" class="form-control single-input-lbl" id="email" placeholder="27/01/2019"> -->
					      <div class="input-group date single-input-lbl">
			                  <div class="input-group-addon date-icon input-sm basic-detail-input-style">
			                    <i class="fa fa-calendar"></i>
			                  </div>
		                  	  <input type="text" class="form-control pull-right date-input input-sm basic-detail-input-style" name="dateOfBirth" id="dateOfBirth">
		                  </div>
					</div>

					<div class="form-group">
					  <label for="gender" class="control-label">@lang('createUserForm.hi.gender') / @lang('createUserForm.en.gender')<span class="required">*</span></label>
					  	<select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="gender" id="gender">
					  		<option value="" selected disabled>Please Select Employee's gender</option>
						    <option value="Male">Male</option>
						    <option value="Female">Female</option>
						    <option value="Other">Other</option>
						  </select>
					</div>

					<div class="form-group">
					    <label class="control-label" for="vehicleNumber">@lang('createUserForm.hi.vehicleNumber') / @lang('createUserForm.en.vehicleNumber') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="vehicleNumber" id="vehicleNumber" placeholder="Enter vehicle number">
					</div>

				</div>
					
				<div class="col-md-6">
					<div class="form-group">
					  <label for="maritalStatus" class="control-label">@lang('createUserForm.hi.maritalStatus') / @lang('createUserForm.en.maritalStatus')<span class="required">*</span></label>
					  	<select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="maritalStatus" id="maritalStatus">
					  		<option value="" selected disabled>Please Select marital status</option>
						    <option value="Married">Married</option>
						    <option value="Unmarried">Unmarried</option>
						  </select>
					</div>
					<div class="form-group">
					    <label class="control-label" for="spouseName">@lang('createUserForm.hi.spouseName') / @lang('createUserForm.en.spouseName')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="spouseName" id="spouseName" placeholder="Enter father/ husband's name">
					</div>
					
					<div class="form-group">
					    <label class="control-label" for="panNumber">@lang('createUserForm.hi.panNumber') / @lang('createUserForm.en.panNumber')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="panNumber" id="panNumber" placeholder="Enter PAN number">
					</div>

					<div class="form-group">
					    <label class="control-label" for="adhaarNumber">@lang('createUserForm.hi.adhaarNumber') / @lang('createUserForm.en.adhaarNumber')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="adhaarNumber" id="adhaarNumber" placeholder="Enter adhaar number">
					</div>

					<div class="form-group">
					    <label class="control-label" for="mobileNumberOfficial">@lang('createUserForm.hi.mobileNumberOfficial') / @lang('createUserForm.en.mobileNumberOfficial')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="mobileNumberOfficial" id="mobileNumberOfficial" placeholder="Enter mobile number">
					</div>

					<div class="form-group">
					    <label class="control-label" for="mobileNumberPersonal">@lang('createUserForm.hi.mobileNumberPersonal') / @lang('createUserForm.en.mobileNumberPersonal')<span class="required">*</span></label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="mobileNumberPersonal" id="mobileNumberPersonal" placeholder="Enter mobile number personal">
					</div>

					<div class="form-group">
					    <label class="control-label" for="emailOfficial">@lang('createUserForm.hi.emailOfficial') / @lang('createUserForm.en.emailOfficial')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="emailOfficial" id="emailOfficial" placeholder="Enter your official email id">
					</div>

					<div class="form-group">
					    <label class="control-label" for="emailPersonal">@lang('createUserForm.hi.emailPersonal') / @lang('createUserForm.en.emailPersonal')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="emailPersonal" id="emailPersonal" placeholder="Enter your email">
					</div>

					<div class="form-group">
					    <label class="control-label" for="emergencyContactName">@lang('createUserForm.hi.emergencyContactName') / @lang('createUserForm.en.emergencyContactName')<span class="required">*</span></label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="emergencyContactName" id="emergencyContactName" placeholder="Enter Emergency Contact Name">
					</div>

					<div class="form-group">
					    <label class="control-label" for="contactNoEmergency">@lang('createUserForm.hi.contactNoEmergency') / @lang('createUserForm.en.contactNoEmergency')<span class="required">*</span></label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="contactNoEmergency" id="contactNoEmergency" placeholder="Enter your number">
					</div>

					<div class="form-group">
					    <label class="control-label" for="relationshipId">@lang('createUserForm.hi.relationshipId') / @lang('createUserForm.en.relationshipId')</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="relationshipId" id="relationshipId" placeholder="Enter relationship">
					</div>

					<div class="form-group">
					    <label class="control-label" for="bloodGroupId">@lang('createUserForm.hi.bloodGroupId') / @lang('createUserForm.en.bloodGroupId')</label>
					      <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="bloodGroupId" id="bloodGroupId">
					      	<option value="" selected disabled>Please Select Employee's blood group</option>
						    <option>A−</option>
						    <option>A+</option>
						    <option>B−</option>
						    <option>B+</option>
						    <option>AB−</option>
						    <option>AB+</option>
						    <option>O−</option>
						    <option>O+</option>
						  </select>
					</div>

					<div class="form-group">
					    <label class="control-label" for="supervisor">@lang('createUserForm.hi.supervisor') / @lang('createUserForm.en.supervisor') <span class="required">*</span></label>
					      <select class="form-control single-input-lbl only-dropdown-input input-sm basic-detail-input-style" name="supervisor" id="supervisor">
					      	<option value="" selected disabled>Please Select Employee's supervisor</option>
						    <option>admin</option>
						    <option>employee</option>
						  </select>	
					</div>
					
				</div>

			</div>

			<div class="address-detail">
				<span class="addressDetail" name="addressDetail" id="addressDetail" >@lang('createUserForm.hi.addressDetail') / @lang('createUserForm.en.addressDetail')</span>
			</div>
			
			<div class="row address-details-inputs">
				<div class="col-md-6">
					<p class="currentAddress" id="currentAddress" name="currentAddress" >@lang('createUserForm.hi.currentAddress') / @lang('createUserForm.en.currentAddress') :</p>
					<hr>
					<div class="form-group">
					    <label class="control-label" for="currentAddressOne">@lang('createUserForm.hi.currentAddressOne') / @lang('createUserForm.en.currentAddressOne') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="currentAddressOne" id="currentAddressOne" placeholder="Enter your address">
					</div>

					<div class="form-group">
					    <label class="control-label" for="currentAddressTwo">@lang('createUserForm.hi.currentAddressTwo') / @lang('createUserForm.en.currentAddressTwo') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="currentAddressTwo" id="currentAddressTwo" placeholder="Enter your address">
					</div>

					<div class="form-group">
					    <label class="control-label" for="currentAddressCity">@lang('createUserForm.hi.currentAddressCity') / @lang('createUserForm.en.currentAddressCity') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="currentAddressCity" id="currentAddressCity" placeholder="Enter Address City">
					</div>

					<div class="form-group">
					    <label class="control-label" for="currentAddressPin">@lang('createUserForm.hi.currentAddressPin') / @lang('createUserForm.en.currentAddressPin') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="currentAddressPin" id="currentAddressPin" placeholder="Enter Current Address PIN">
					</div>
				</div>
				
				<div class="col-md-6">
					<p class="p-a" name="permanentAddress" id="permanentAddress" >@lang('createUserForm.hi.permanentAddress') / @lang('createUserForm.en.permanentAddress') :</p>
					<div class="checkbox p-a-checkbox">
                      <label>
                        <input type="checkbox"> Same as Current Address
                      </label>
                    </div>
					<hr>
					<div class="form-group">
					    <label class="control-label" for="permanentAddressOne">@lang('createUserForm.hi.permanentAddressOne') / @lang('createUserForm.en.permanentAddressOne') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="permanentAddressOne" id="permanentAddressOne" placeholder="Enter your address">
					</div>

					<div class="form-group">
					    <label class="control-label" for="permanentAddressTwo">@lang('createUserForm.hi.permanentAddressTwo') / @lang('createUserForm.en.permanentAddressTwo') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="permanentAddressTwo" id="permanentAddressTwo" placeholder="Enter your address">
					</div>

					<div class="form-group">
					    <label class="control-label" for="permanentAddressCity">@lang('createUserForm.hi.permanentAddressCity') / @lang('createUserForm.en.permanentAddressCity') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="permanentAddressCity" id="permanentAddressCity" placeholder="Enter Address City">
					</div>

					<div class="form-group">
					    <label class="control-label" for="permanentAddressPin">@lang('createUserForm.hi.permanentAddressPin') / @lang('createUserForm.en.permanentAddressPin') :</label>
					      <input type="text" class="form-control single-input-lbl input-sm basic-detail-input-style" name="permanentAddressPin" id="permanentAddressPin" placeholder="Enter Current Address PIN">
					</div>
				</div>
			</div>
					
			<div class="row submit-btn-box">
				<!-- <input type="submit" name="" value="submit" class="submit-btn">
				<input type="submit" name="" value="Cancel" class="submit-btn"> -->
				<button type="submit" class="btn btn-md btn-primary submit-btn" name="submitleave" id = "submitleave">@lang('createUserForm.hi.submitleave') / @lang('createUserForm.en.submitleave') </button>
				<button type="submit" class="btn btn-md btn-default submit-btn" name="cancelleave" id ="cancelleave">@lang('createUserForm.hi.cancelleave') / @lang('createUserForm.en.cancelleave')</button>
			</div>
					
			</form>
		</div>

	</div>
<script type="text/javascript">


	$("#createUserForm").validate({
		rules : {
			"unitId" : {
				required : true
			},
			"employeeCode" : {
				required : true
			},
			"firstName" : {
				required : true
			},
			"designation" : {
				required : true
			},
			"qualification" : {
				required : true
			},
			"dateOfJoining" : {
				required : true
			},
			"gender" : {
				required : true
			},
			"maritalStatus" : {
				required : true
			},
			"mobileNumberPers" : {
				required : true,
				digits: true,
              	exactlengthdigits : 10
			},
			"emrgncyContName" : {
				required : true
			},
			"contactNoEmgrncy" : {
				required : true
			},
			"supervisor" : {
				required : true
			}
			
		},
		messages : {
			"unitId" : {
				required : 'Please Select Unit.'
			},
			"employeeCode" : {
				required : 'Please enter employee code.'
			},
			"firstName" : {
				required : 'Please enter employee Name.'
			},
			"designation" : {
				required : 'Please Select designation.'
			},
			"qualification" : {
				required : 'Please Select qualification.'
			},
			"dateOfJoining" : {
				required : 'Please Select date of joining.'
			},
			"gender" : {
				required : 'Please Select gender.'
			},
			"maritalStatus" : {
				required : 'Please Select marital status.'
			},
			"mobileNumberPers" : {
				required : 'Please enter mobile no.'
			},
			"emrgncyContName" : {
				required : 'Please enter contact name.'
			},
			"contactNoEmgrncy" : {
				required : 'Please enter emergency contact no.'
			},
			"supervisor" : {
				required : 'Please Select employee supervisor.'
			}

		}

	});

	jQuery.validator.addMethod("exactlength", function(value, element, param) {
       return this.optional(element) || value.length == param;
    }, $.validator.format("Please enter exactly {0} characters."));

    //Date picker
    $('#dateOfJoining').datepicker({

      autoclose: true,

      orientation: "bottom" 

    });

    $('#dateOfBirth').datepicker({

      autoclose: true,

      orientation: "bottom" 

    });
    

    
</script>
</body>
</html>