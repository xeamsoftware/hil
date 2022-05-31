@if(!empty($appliedLeave))
  <table class="table table-striped table-bordered">
        <tr>
          <th style="width: 30%">Field</th>
          <th style="width: 70%">Value</th>
        </tr>
        
        <tr>
          <td><em>Address during leave</em></td>
          <td>
            {{@$appliedLeave->address}}
          </td>
        </tr>

        <tr>
          <td><em>Encashment Status</em></td>
          <td>
            @if(@$appliedLeave->encashment_status)
            {{"Leave encashed"}}
            @else
            {{"Leave taken"}}
            @endif
          </td>
        </tr>

        @if(!empty($appliedLeave->leave_half))
          <tr>
            <td><em>Leave Half</em></td>
            <td>
             {{@$appliedLeave->leave_half}}
            </td>
          </tr>
        @endif

        @if(!empty($appliedLeave->pay_status))
          <tr>
            <td><em>Pay Status</em></td>
            <td>
             {{@$appliedLeave->pay_status}}
            </td>
          </tr>
        @endif

        @if(!empty($appliedLeave->weekoffs))
          <tr>
            <td><em>Selected Weekoffs</em></td>
            <td>
             @foreach($appliedLeave->weekoffs as $off)
                {{date("d/m/Y",strtotime($off))}}
                @if(!$loop->last)
                  {{","}}
                @endif
             @endforeach
            </td>
          </tr>
        @endif

        <tr>
          <td><em>Purpose</em></td>
          <td>
           {{@$appliedLeave->purpose}}
          </td>
        </tr> 

        <tr>
          <td><em>Document(s)</em></td>
          <td>
            @if(!@$appliedLeave->appliedLeaveDocuments->isEmpty())
              @foreach($appliedLeave->appliedLeaveDocuments as $document)
              <a href='{{url("leaves/downloadLeaveDocuments/$document->document_name")}}'><span><i class="fa fa-download" aria-hidden="true"></i></span></a>&nbsp;&nbsp;
              @endforeach
            @else
              {{"None"}}
            @endif
          </td>
        </tr>
        
      </table>
@else
    <span class="text-danger"><strong>No data.</strong></span>
@endif