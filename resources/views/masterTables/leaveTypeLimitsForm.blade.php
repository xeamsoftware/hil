@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Leave Type Limit Form</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-md-12">
              <div class="box box-primary lt-limit-box">
              <!-- /.box-header -->
              <!-- form start -->
              <form action="/action_page.php">
                <table class="table table-striped">
                  <thead>
                    <tr>
                        <th class="ltl-heading1">Leave Type</th>
                          <th class="ltl-heading2">Max Yearly Limit</th>
                          <th class="ltl-heading3">Total Upper limit</th>
                      </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <td>Casual Leave</td>
                      <td><input type="text" class="form-control ltl-input-field" id="cl-myl" placeholder="123" name="cl-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="cl-tul" placeholder="123" name="cl-tul"></td>
                  </tr>
                  <tr>
                      <td>Half Pay Sick Leave (HPSL)</td>
                      <td><input type="text" class="form-control ltl-input-field" id="hpsl-myl" placeholder="123" name="hpsl-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="hpsl-tul" placeholder="123" name="hpsl-tul"></td>
                  </tr>
                  <tr>
                      <td>EL-Non Encashable</td>
                      <td><input type="text" class="form-control ltl-input-field" id="elne-myl" placeholder="123" name="elne-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="elne-tul" placeholder="123" name="elne-tul"></td>
                  </tr>
                  <tr>
                      <td>Compensatory Off</td>
                      <td><input type="text" class="form-control ltl-input-field" id="co-myl" placeholder="123" name="co-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="co-tul" placeholder="123" name="co-tul"></td>
                  </tr>
                  <tr>
                    <td>Sterlisation Leave</td>
                      <td><input type="text" class="form-control ltl-input-field" id="sl-myl" placeholder="123" name="sl-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="sl-tul" placeholder="123" name="sl-tul"></td>
                  </tr>
                  <tr>
                    <td>Blood Donation</td>
                      <td><input type="text" class="form-control ltl-input-field" id="bd-myl" placeholder="123" name="bd-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="bd-tul" placeholder="123" name="bd-tul"></td>
                  </tr>
                  <tr>
                    <td>Quarantine Leave</td>
                      <td><input type="text" class="form-control ltl-input-field" id="ql-myl" placeholder="123" name="ql-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="ql-tul" placeholder="123" name="ql-tul"></td>
                  </tr>
                  <tr>
                    <td>Maternity Leave</td>
                      <td><input type="text" class="form-control ltl-input-field" id="ml-myl" placeholder="123" name="ml-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="ml-tul" placeholder="123" name="ml-tul"></td>
                  </tr>
                  <tr>
                    <td>Paternity Leave</td>
                      <td><input type="text" class="form-control ltl-input-field" id="pl-myl" placeholder="123" name="pl-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="pl-tul" placeholder="123" name="pl-tul"></td>
                  </tr>
                  <tr>
                    <td>Extra Ordinary Leave (EOL)</td>
                      <td><input type="text" class="form-control ltl-input-field" id="eol-myl" placeholder="123" name="eol-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="eol-tul" placeholder="123" name="eol-tul"></td>
                  </tr>
                  <tr>
                    <td>EL-Encashable</td>
                      <td><input type="text" class="form-control ltl-input-field" id="ele-myl" placeholder="123" name="ele-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="ele-tul" placeholder="123" name="ele-tul"></td>
                  </tr>
                  <tr>
                    <td>Restricted Holiday (RH)</td>
                      <td><input type="text" class="form-control ltl-input-field" id="rh-myl" placeholder="123" name="rh-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="rh-tul" placeholder="123" name="rh-tul"></td>
                  </tr>
                  <tr>
                    <td>Transfer/Joining Leave</td>
                      <td><input type="text" class="form-control ltl-input-field" id="tjl-myl" placeholder="123" name="tjl-myl"></td>
                      <td><input type="text" class="form-control ltl-input-field" id="tjl-tul" placeholder="123" name="tjl-tul"></td>
                  </tr>
                  </tbody>
                </table>
                
                <div class="box-footer footer-btn-center">
                  <button type="submit" class="btn btn-primary submit-btn-style">Submit</button>
                  <a href="#" class="cancel-btn-styling">Cancel</a>
                </div>
              </form>

                </div>
                <!-- /.box-body -->
            </div>
          </div>
      <!-- /.row -->
      <!-- Main row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection