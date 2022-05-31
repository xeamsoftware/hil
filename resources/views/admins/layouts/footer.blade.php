<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <!-- <b>Version</b> 2.4.0 -->
    </div>
    <strong>Copyright &copy; {{date("Y")}} </strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    
    
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery UI 1.11.4 -->

<script src="{{asset('public/admin_assets/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>

  $.widget.bridge('uibutton', $.ui.button);

</script>


<!-- Select2 -->

<script src="{{asset('public/admin_assets/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<!-- Slimscroll -->

<script src="{{asset('public/admin_assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>

<!-- FastClick -->

<script src="{{asset('public/admin_assets/bower_components/fastclick/lib/fastclick.js')}}"></script>

<!-- AdminLTE App -->

<script src="{{asset('public/admin_assets/dist/js/adminlte.js')}}"></script>


<script type="text/javascript">

  $("div.alert-dismissible").fadeOut(6000);


</script>



<script>

  //jQuery.noConflict();



  $(function () {



    //Initialize Select2 Elements

    $('.select2').select2()



    //Date picker

    // $('.datepicker').datepicker({

    //   autoclose: true,

    //   orientation: "bottom" 

    // })

    
    

  });

  $("select").on("select2:close", function (e) {  
        $(this).valid(); 
  });

</script>
</body>
</html>