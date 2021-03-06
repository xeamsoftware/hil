@extends('admins.layouts.app')



@section('content')



<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper content-aside">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        All Messages

        <!-- <small>Control panel</small> -->

      </h1>

      <ol class="breadcrumb">

        <li><a href="{{ route('employees.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>

        <!-- <li class="active">Dashboard</li> -->

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">
      <div class="row">

        <div class="col-sm-12">

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>&nbsp;&nbsp;<span class="label label-success">{{$notifications->total()}}</span>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                @if(!$notifications->isEmpty())
                <!-- Check all button -->
                <button title="Select all" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                  <button title="Delete" type="button" class="btn btn-default btn-sm deleteBtn"><i class="fa fa-trash-o"></i></button>
                <!-- /.pull-right -->
                @endif
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                @if(!$notifications->isEmpty())    
                  @foreach($notifications as $notification)  
                  <tr>
                    <td><input type="checkbox" class="allCheckboxes" value="{{$notification->id}}"></td>
                    <td class="mailbox-name">{{@$notification->sender->first_name}} {{@$notification->sender->middle_name}} {{@$notification->sender->last_name}}</td>
                    <td class="mailbox-subject"><b>{{@$notification->label}}</b> - 
                      <span title="{{@$notification->message}}">
                        @if(strlen($notification->message) > 45)
                          {{substr(@$notification->message,0,45)}}...
                        @else
                          {{@$notification->message}}
                        @endif
                      </span>
                    </td>
                    <td class="mailbox-date">{{date("m/d/Y H:i:s",strtotime(@$notification->created_at))}}</td>
                  </tr>
                  @endforeach
                @else
                  <span class="text-info">&nbsp;{{"No Messages."}}</span>
                @endif  
                  
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                @if(!$notifications->isEmpty()) 
                <!-- Check all button -->
                <button title="Select All" type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <button title="Delete" type="button" class="btn btn-default btn-sm deleteBtn"><i class="fa fa-trash-o"></i></button>

                @endif  

                <div class="pull-right">
                  <div class="btn-group">
                  @if(!$notifications->isEmpty())  
                    {{ $notifications->links() }}
                  @endif  
                  </div> 

                </div>
              </div>
            </div>
          </div>

          <!-- /. box -->

        </div>

        <!-- /.col -->

        

      </div>

      <!-- /.row -->



    </section>

    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->



  <script type="text/javascript">

    //Enable check and uncheck all functionality

    $(".checkbox-toggle").on('click',function () {

      $(this).toggleClass("active");

      if($(this).hasClass("active")){
        $(".checkbox-toggle .fa").removeClass("fa-square-o").addClass('fa-check-square-o');
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
        $(".allCheckboxes").prop("checked", true);

      }else{

        $(".checkbox-toggle .fa").removeClass("fa-check-square-o").addClass('fa-square-o');
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
        $(".allCheckboxes").prop("checked", false);

      }

    });



    $(".deleteBtn").on('click',function(){
      
      var selectedIds = [];
      
      $.each($(".allCheckboxes:checkbox:checked"), function(){            
        selectedIds.push(Number($(this).val()));
      });

      if(selectedIds.length > 0){
          
        if (!confirm("Are you sure you want to delete these message(s)?")) {
          return false; 
        }else{
          $.ajax({
            type: 'POST',
            url: "{{route('employees.deleteMessages')}}",
            data: {selectedIds: selectedIds},
            success: function(result){
              if(result.status){
                window.location.reload(true);
              }
            }
          });
        }
      }else{
        alert("Please select atleast one message.");
      }  
    });

  </script>

  @endsection