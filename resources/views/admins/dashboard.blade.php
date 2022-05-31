@extends('admins.layouts.app')



@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-aside">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('employees.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="dashboard-table1">
      <table class="table table-striped table-responsive">
                  <thead>
                    <tr>
                        <th class="ltl-heading1">Leave Type</th>
                          <th class="ltl-heading2">Total Accumulated Leaves</th>
                          <th class="ltl-heading2">Max. Yearly Credited Leaves</th>
                          <th class="ltl-heading2">Yearly Available Leaves</th>
                          <th class="ltl-heading3">Days/ Hrs</th>
                      </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <td>@lang('leaveAccumulationForm.hi.casualLeave') / @lang('leaveAccumulationForm.en.casualLeave')</td>
                      <td class="center-table-text">
                        @if(!empty($data[1]['leaveAccumulation'])){{$data[1]['leaveAccumulation']->total_remaining_count}}@else{{"0"}}@endif
                      </td> 
                      <td class="center-table-text">12</td>
                      <td class="center-table-text">
                        {{@$data[1]['yearlyBalanceLeaves']}}
                      </td> 
                      <td class="center-table-text">Days</td>
                  </tr>
                  <tr>
                      <td>@lang('leaveAccumulationForm.hi.compensatoryLeave') / @lang('leaveAccumulationForm.en.compensatoryLeave')</td>
                      <td class="center-table-text">
                        @if(!empty($data[4]['leaveAccumulation'])){{$data[4]['leaveAccumulation']->total_remaining_count/0.125}}@else{{"0"}}@endif
                      </td>
                      <td class="center-table-text">NA</td>
                      <td class="center-table-text">
                        {{@$data[4]['yearlyBalanceLeaves']}}
                      </td>  
                      <td class="center-table-text">Hrs</td>
                  </tr>
                  <tr>
                      <td>@lang('leaveAccumulationForm.hi.nonEncashable') / @lang('leaveAccumulationForm.en.nonEncashable')</td>
                      <td class="center-table-text">
                         @if(!empty($data[3]['leaveAccumulation'])){{$data[3]['leaveAccumulation']->total_remaining_count}}@else{{"0"}}@endif
                      </td>
                      <td class="center-table-text">15</td>
                      <td class="center-table-text">
                        {{@$data[3]['yearlyBalanceLeaves']}}
                      </td>
                      <td class="center-table-text">Days</td>
                  </tr>
                  <tr>
                    <td>@lang('leaveAccumulationForm.hi.encashable') / @lang('leaveAccumulationForm.en.encashable')</td>
                    <td class="center-table-text">
                      @if(!empty($data[11]['leaveAccumulation'])){{$data[11]['leaveAccumulation']->total_remaining_count}}@else{{"0"}}@endif
                    </td>  
                    <td class="center-table-text">15</td>
                    <td class="center-table-text">
                      {{@$data[11]['yearlyBalanceLeaves']}}
                    </td>
                    <td class="center-table-text">Days</td>
                  </tr>
                  <tr>
                    <td>@lang('leaveAccumulationForm.hi.restrictedHoliday') / @lang('leaveAccumulationForm.en.restrictedHoliday')</td>
                    <td class="center-table-text">
                      @if(!empty($data[12]['leaveAccumulation'])){{$data[12]['leaveAccumulation']->total_remaining_count}}@else{{"0"}}@endif
                    </td>  
                    <td class="center-table-text">2</td>
                    <td class="center-table-text">
                      {{@$data[12]['yearlyBalanceLeaves']}}
                    </td>
                    <td class="center-table-text">Days</td>
                  </tr>
                  <tr>
                    <td>@lang('leaveAccumulationForm.hi.shortLeave') / @lang('leaveAccumulationForm.en.shortLeave')</td>
                    <td class="center-table-text">
                     @if(!empty($data[14]['leaveAccumulation'])){{$data[14]['leaveAccumulation']->total_remaining_count/0.125}}@else{{"0"}}@endif
                    </td> 
                    <td class="center-table-text">NA</td>
                    <td class="center-table-text">
                      {{@$data[14]['yearlyBalanceLeaves']}}
                    </td>
                    <td class="center-table-text">Hrs</td>
                  </tr>
                  <tr>
                    <td>@lang('leaveAccumulationForm.hi.halfPaySickLeave') / @lang('leaveAccumulationForm.en.halfPaySickLeave')</td>
                    <td class="center-table-text">
                      @if(!empty($data[2]['leaveAccumulation'])){{$data[2]['leaveAccumulation']->total_remaining_count}}@else{{"0"}}@endif
                    </td> 
                    <td class="center-table-text">20</td>
                    <td class="center-table-text">
                      {{@$data[2]['yearlyBalanceLeaves']}}
                    </td> 
                    <td class="center-table-text">Days</td>
                  </tr>
                  </tbody>
                </table>
            </div>

                <div class="Others-leave-type dashboard-table2">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="ltl-heading1">Other Leave Types</th>
                        <th class="ltl-heading2">Information about Leave Types</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.sterlisationLeave') / @lang('leaveAccumulationForm.en.sterlisationLeave')</td>
                        <td class="center-table-text">Male - 9 Days & Female - 14 Days</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.bloodDonation') / @lang('leaveAccumulationForm.en.bloodDonation')</td>
                        <td class="center-table-text">As Per Policy</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.quarantineLeave') / @lang('leaveAccumulationForm.en.quarantineLeave')</td>
                        <td class="center-table-text">15 Days Leave only for M&S Employees</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.maternityLeave') / @lang('leaveAccumulationForm.en.maternityLeave')</td>
                        <td class="center-table-text">As Per Policy</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.paternityLeave') / @lang('leaveAccumulationForm.en.paternityLeave')</td>
                        <td class="center-table-text">As Per Policy</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.extraOrdinaryLeave') / @lang('leaveAccumulationForm.en.extraOrdinaryLeave')</td>
                        <td class="center-table-text">As Per Policy</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.transferJoiningLeave') / @lang('leaveAccumulationForm.en.transferJoiningLeave')</td>
                        <td class="center-table-text">As Per Policy</td>
                      </tr>
                      <tr>
                        <td>@lang('leaveAccumulationForm.hi.specialCasualLeave') / @lang('leaveAccumulationForm.en.specialCasualLeave')</td>
                        <td class="center-table-text">As Per Policy</td>
                      </tr>
                    </tbody>
                  </table>



                  <!--   <h4 class="leave-others">Others Leave Type:</h4>
                    <p>@lang('leaveAccumulationForm.hi.sterlisationLeave') / @lang('leaveAccumulationForm.en.sterlisationLeave')</p>
                    <p>@lang('leaveAccumulationForm.hi.bloodDonation') / @lang('leaveAccumulationForm.en.bloodDonation')</p>
                    <p>@lang('leaveAccumulationForm.hi.quarantineLeave') / @lang('leaveAccumulationForm.en.quarantineLeave')</p>
                    <p>@lang('leaveAccumulationForm.hi.maternityLeave') / @lang('leaveAccumulationForm.en.maternityLeave')</p>
                    <p>@lang('leaveAccumulationForm.hi.paternityLeave') / @lang('leaveAccumulationForm.en.paternityLeave')</p>
                    <p>@lang('leaveAccumulationForm.hi.extraOrdinaryLeave') / @lang('leaveAccumulationForm.en.extraOrdinaryLeave')</p>
                    <p>@lang('leaveAccumulationForm.hi.transferJoiningLeave') / @lang('leaveAccumulationForm.en.transferJoiningLeave')</p>
                    <p>@lang('leaveAccumulationForm.hi.specialCasualLeave') / @lang('leaveAccumulationForm.en.specialCasualLeave')</p> -->
                </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection