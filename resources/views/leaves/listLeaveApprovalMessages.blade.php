@if(!$data->isEmpty())

<ul class="timeline timeline-inverse">

    @foreach($data as $key => $value)

    <li>

      <i class="fa fa-envelope bg-blue"></i>

      <div class="timeline-item">

        <span class="time"><i class="fa fa-clock-o"></i> {{date("d/m/Y H:i:s",strtotime($value->created_at))}}</span>

        <h5 class="timeline-header"><span class="leaveMessageList"><strong class="text-success">Send by</strong> &nbsp;{{$value->sender->first_name}} {{$value->sender->middle_name}} {{$value->sender->last_name}}</span></h5>

        <div class="timeline-body">

          <span class="leaveMessageList"><strong class="text-danger">Received by</strong> &nbsp;{{$value->receiver->first_name}} {{$value->receiver->middle_name}} {{$value->receiver->last_name}}</span><br><br>

          {{$value->message}}

        </div>

      </div>

    </li>

    @endforeach

    <li>

      <i class="fa fa-clock-o bg-gray"></i>

    </li>

  </ul>

  @else

      <span class="text-danger"><strong>No message.</strong></span>

  @endif