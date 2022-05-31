  
  <table class="table table-striped table-bordered">
        <thead>
                    <tr>
                        <th class="ltl-heading1">Leave Type</th>
                          <th class="ltl-heading2">Total Accumulated Leaves</th>
                          <th class="ltl-heading2">Max. Yearly Credited Leaves</th>
                          <th class="ltl-heading2">Yearly Balance Leaves</th>
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
