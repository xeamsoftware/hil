

<!-- Left side column. contains the logo and sidebar -->

<aside class="main-sidebar sidebar-changes">

    <!-- sidebar: style can be found in sidebar.less -->

    @php

        $user = Auth::user();

    @endphp

    <section class="sidebar">

        <!-- Sidebar user panel -->

        <div class="user-panel">

            <div class="pull-left image">

                <img src="{{$user->profile_pic}}" class="img-circle" alt="User Image">

            </div>

            <div class="pull-left info">

                <p>{{$user->first_name}} {{$user->last_name}}</p>

                <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a>

            </div>

        </div>

        <!-- search form -->



        <!-- /.search form -->

        <!-- sidebar menu: : style can be found in sidebar.less -->

        <ul class="sidebar-menu" data-widget="tree">

            <li class="header" id="mainNavigation">@lang('sidebar.hi.mainNavigation') / @lang('sidebar.en.mainNavigation') </li>

            <li>

                <a href="{{route('employees.dashboard')}}">



                    <i class="fa fa-dashboard" id="dashBoard"></i> <span>@lang('sidebar.hi.dashBoard') / @lang('sidebar.en.dashBoard')</span>



                </a>



            </li>



            <li class="treeview">



                <a href="javascript:void(0)">



                    <i class="fa fa-users"></i> <span id="employeeManage" >@lang('sidebar.hi.employeeManagement') / <br> @lang('sidebar.en.employeeManagement')</span>



                    <span class="pull-right-container" >



              <i class="fa fa-angle-left pull-right"></i>



            </span>



                </a>



                <ul class="treeview-menu">



                    @can('create-user')



                        <li class=""><a href="{{route('employees.create')}}"><i class="fa fa-circle-o text-red"></i>@lang('sidebar.hi.newRegistration') /  @lang('sidebar.en.newRegistration')</a></li>

                        <li class=""><a href="{{route('employees.retirement.list')}}"><i class="fa fa-circle-o text-red"></i>@lang('sidebar.hi.retirementList') /  @lang('sidebar.en.retirementList')</a></li>


                        <li class=""><a href="{{route('employees.employeeTransfer')}}"><i class="fa fa-circle-o text-green"></i>@lang('sidebar.hi.employeeTransfer') / <br>  @lang('sidebar.en.employeeTransfer')</a></li>



                        <li class=""><a href="{{route('leaves.leaveAccumulationsForm')}}"><i class="fa fa-circle-o text-maroon"></i>@lang('sidebar.hi.leaveAccumulations') / <br>  @lang('sidebar.en.leaveAccumulations')</a></li>



                    @endcan



                    @can('import-user')

                        <li class=""><a href="{{route('employees.importUsers')}}"><i class="fa fa-circle-o text-yellow"></i>@lang('sidebar.hi.importUsers') / <br>  @lang('sidebar.en.importUsers')</a></li>



                        <li class=""><a href="{{route('employees.importLeaveAccumulations')}}"><i class="fa fa-circle-o text-magenta"></i>@lang('sidebar.hi.importLeaveAccumulations') / <br>  @lang('sidebar.en.importLeaveAccumulations')</a></li>

                    @endcan



                    <li class=""><a href="{{route('employees.list')}}"><i class="fa fa-circle-o text-aqua" id="employeeList"></i>@lang('sidebar.hi.employeeList') /  @lang('sidebar.en.employeeList')</a></li>







                </ul>



            </li>



            @can('manage-masterTable')

                <li>

                    <a href="{{route('masterTables.list')}}">

                        <i class="fa fa-files-o"></i> <span id="masterManagement">@lang('sidebar.hi.masterManagement') /  @lang('sidebar.en.masterManagement')</span>

                    </a>

                </li>

            @endcan



            <li class="treeview">



                <a href="javascript:void(0)">



                    <i class="fa fa-plane fa-lg"></i> <span id="leaveManagement">@lang('sidebar.hi.leaveManagement') / @lang('sidebar.en.leaveManagement')</span>



                    <span class="pull-right-container">



              <i class="fa fa-angle-left pull-right"></i>



            </span>



                </a>



                <ul class="treeview-menu">



                    @can('apply-leave')

                        <li class=""><a href="{{route('leaves.listCompensatoryLeaves')}}"><i class="fa fa-circle-o text-magenta" id="compensatoryLeave"></i>@lang('sidebar.hi.compensatoryLeave') /<br> @lang('sidebar.en.compensatoryLeave')</a></li>

                    @endcan

                    @can('apply-leave')

                    <!-- <li class=""><a href="{{route('leaves.listCallOfExtraDutyLeaves')}}"><i class="fa fa-circle-o text-magenta" id="compensatoryLeave"></i>@lang('sidebar.hi.callOfExtraDutyLeave') /<br> @lang('sidebar.en.callOfExtraDutyLeave')</a></li> -->

                    @endcan



                    @if(auth()->user()->can('approve-leave') || auth()->user()->can('verify-attendance'))

                        <li class=""><a href="{{route('leaves.listCompensatoryLeaveApprovals')}}"><i class="fa fa-circle-o text-teal" id="VerifyCompensatoryLeave"></i>@lang('sidebar.hi.VerifyCompensatoryLeave')<br>  @lang('sidebar.en.VerifyCompensatoryLeave')</a></li>
                    <!-- <li class=""><a href="{{route('leaves.callOfExtraDuty.approvals')}}"><i class="fa fa-circle-o text-teal" id="VerifyCallOfExtraDutyLeave"></i>@lang('sidebar.hi.VerifyCallOfExtraDutyLeave')<br>  @lang('sidebar.en.VerifyCallOfExtraDutyLeave')</a></li> -->

                    @endif



                    @can('apply-leave')

                        <li class=""><a href="{{route('leaves.applyLeave')}}"><i class="fa fa-circle-o text-red" id="applyForLeave"></i>@lang('sidebar.hi.applyForLeave') / <br>  @lang('sidebar.en.applyForLeave')</a></li>



                        <li class=""><a href="{{route('leaves.listAppliedLeaves')}}"><i class="fa fa-circle-o text-aqua" id="appliedLeaves"></i>@lang('sidebar.hi.appliedLeaves') /   @lang('sidebar.en.appliedLeaves')</a></li>

                    @endcan


                    @can('add-leave')
                        <li class=""><a href="{{route('leaves.addLeave')}}"><i class="fa fa-circle-o text-red" id="applyForLeave"></i>@lang('sidebar.hi.addLeave') / <br>  @lang('sidebar.en.addLeave')</a></li>
                    @endcan


                    @can('approve-leave')



                        <li class=""><a href="{{route('leaves.listAppliedLeaveApprovals')}}"><i class="fa fa-circle-o text-success" id="approveLeave"></i>@lang('sidebar.hi.approveLeave') /   @lang('sidebar.en.approveLeave')</a></li>

                    @endcan

                    @can('generate-leaveReport')

                        <li class=""><a href="{{route('leaves.leaveReportForm')}}"><i class="fa fa-circle-o text-red" id="leaveReport"></i>@lang('sidebar.hi.leaveReport') /  @lang('sidebar.en.leaveReport')</a></li>



                    @endcan



                    <li class=""><a href="{{route('leaves.listHolidays')}}"><i class="fa fa-circle-o text-secondary" id="holidayList"></i>@lang('sidebar.hi.holidayList') /  @lang('sidebar.en.holidayList')</a></li>



                </ul>



            </li>



            @can('reset-password')

                <li>

                    <a href="{{route('passwordResetRequests.list')}}">

                        <i class="fa fa-key"></i> <span id="passwordResetRequests">@lang('sidebar.hi.passwordResetRequests') /<br>  @lang('sidebar.en.passwordResetRequests')</span>

                    </a>

                </li>

            @endcan





        </ul>

    </section>

    <!-- /.sidebar -->

</aside>







<script type="text/javascript">



    /** add active class and stay opened when selected */



    var url = window.location;







    // for sidebar menu entirely but not cover treeview



    $('ul.sidebar-menu a').filter(function() {



        return this.href == url;



    }).parent().addClass('active');







    // for treeview



    $('ul.treeview-menu a').filter(function() {



        return this.href == url;



    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');







</script>
