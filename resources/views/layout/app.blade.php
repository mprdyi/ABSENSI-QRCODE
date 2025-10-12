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
  <link href="{{ ('Asset/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
<link href="{{ asset('Asset/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/mycss.css') }}" rel="stylesheet">


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

    <!-- Custom scripts for all pages -->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('Asset/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

    <!-- Bootstrap 5 Bundle JS (sudah termasuk Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
 const tabManual = document.getElementById('tabManual');
  const tabCSV = document.getElementById('tabCSV');
  const formManual = document.getElementById('formManual');
  const formCSV = document.getElementById('formCSV');

  tabManual.addEventListener('click', () => {
    tabManual.classList.add('active');
    tabCSV.classList.remove('active');
    formManual.classList.remove('d-none');
    formCSV.classList.add('d-none');
  });

  tabCSV.addEventListener('click', () => {
    tabCSV.classList.add('active');
    tabManual.classList.remove('active');
    formCSV.classList.remove('d-none');
    formManual.classList.add('d-none');
  });

const fileInput = document.getElementById('file');
  const fileName = document.getElementById('file-name');
  const dropZone = document.querySelector('.custom-file-upload');

  fileInput.addEventListener('change', () => {
    fileName.textContent = fileInput.files[0]?.name || 'Pilih file atau seret ke sini';
  });

  // drag & drop (opsional biar lebih keren)
  dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
  });
  dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
  dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    fileName.textContent = e.dataTransfer.files[0]?.name || 'Pilih file atau seret ke sini';
  });
</script>


</body>

</html>
