<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>@yield('title')</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('Asset/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
    <link href="{{ asset('Asset/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mycss.css') }}" rel="stylesheet">
<style>
    footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #111;
    text-align: center;
    padding: 10px;
    z-index:-1;
    padding:10px;
}
</style>

</head>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
     @include('layout.app.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
    @include('layout.app.navbar')

    @yield('content')


    @include('layout.app.footer')
    </div>
    </div>


    <!-- End of Main Content -->
    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('Asset/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('Asset/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript -->
    <script src="{{ asset('Asset/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins
    <script src="{{ asset('Asset/chart.js/Chart.min.js') }}"></script> -->

    <!-- Page level custom scripts
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>  -->

    <script src="{{ asset('js/my-js.js') }}"></script>

    <!-- Bootstrap 5 Bundle JS (sudah termasuk Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script AJAX -->
<script>
$(document).ready(function () {
    $('select[name="id_kelas"]').on('change', function () {
        let kelasId = $(this).val();
        let waliInput = $('input[name="wali_kelas"]');
        let idWaliHidden = $('input[name="id_wali_kelas"]');

        waliInput.val('Loading...');
        idWaliHidden.val('');

        if (kelasId) {
            $.ajax({
                url: '/get-wali-kelas/' + kelasId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.wali) {
                        waliInput.val(data.wali.nama);
                        idWaliHidden.val(data.wali.id);
                    } else {
                        waliInput.val('(Tidak ada wali kelas)');
                        idWaliHidden.val('');
                    }
                },
                error: function () {
                    waliInput.val('(Gagal mengambil data)');
                }
            });
        } else {
            waliInput.val('');
            idWaliHidden.val('');
        }
    });
});

</script>

</body>
</html>
