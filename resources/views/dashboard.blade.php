<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>TimReports</title>
    <!-- Tell the browser to be responsive to screen width -->
    <link
      rel="shortcut icon"
      href="https://expidetufactura.com.mx/XPD/img/favicon.ico"
    />
    <meta
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
      name="viewport"
    />
    <!-- Bootstrap 3.3.7 -->
    <link
      rel="stylesheet"
      href="adminLte/bower_components/bootstrap/dist/css/bootstrap.min.css"
    />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="adminLte/bower_components/font-awesome/css/font-awesome.min.css"
    />
    <!-- Ionicons -->
    <link
      rel="stylesheet"
      href="adminLte/bower_components/Ionicons/css/ionicons.min.css"
    />
    <!-- jvectormap -->
    <link
      rel="stylesheet"
      href="adminLte/bower_components/jvectormap/jquery-jvectormap.css"
    />
    <!-- Theme style -->
    <link rel="stylesheet" href="adminLte/css/AdminLTE.min.css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="adminLte/css/skins/_all-skins.min.css" />

    <link
      rel="stylesheet"
      href="https://unpkg.com/vue-on-toast@1.0.0-alpha.1/dist/vue-on-toast.min.css"
    />

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"
    />
  </head>
  <body class="hold-transition skin-ligth-green sidebar-mini layout-boxed">
    <div class="wrapper">
      <div id="app">
        <App-component></App-component>
      </div>
      <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    </div>
  </body>
  <footer class="main-footer">
    <div class="pull-right hidden-xs"><b>Versión</b> 1.0</div>
    <strong>Expide tu factura</strong>
    <div class="control-sidebar-bg"></div>
  </footer>

  <!-- ./wrapper -->
  <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"
  ></script>

  <!-- jQuery 3 -->
  <script src="adminLte/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="adminLte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="adminLte/bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="adminLte/js/adminlte.min.js"></script>
  <!-- Sparkline -->
  <script src="adminLte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
  <!-- SlimScroll -->
  <script src="adminLte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- ChartJS -->
  <script src="adminLte/bower_components/chart.js/Chart.js"></script>
  <script type="text/javascript">
    $("#myModal").on("shown.bs.modal", function() {
      $("#myInput").focus();
    });
  </script>
</html>
