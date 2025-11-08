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
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

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
     <!-- Page level plugins
    <script src="{{ asset('Asset/chart.js/Chart.min.js') }}"></script> -->

    <!-- Page level custom scripts
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>  -->
    <script src="{{ asset('Asset/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('Asset/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript -->
    <script src="{{ asset('Asset/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
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



// INI MENU HAMBURGER
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("accordionSidebar");
  const toggleBtn = document.getElementById("sidebarToggleTop");
  const closeBtn = document.getElementById("closeSidebar");

  if (!sidebar || !toggleBtn) return;

  // ---- Hapus semua event bawaan dari tombol SB Admin 2 ----
  const newToggleBtn = toggleBtn.cloneNode(true);
  toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);

  // ---- Tambahkan event toggle baru kita ----
  newToggleBtn.addEventListener("click", function (e) {
    e.preventDefault();
    sidebar.classList.toggle("active");
  });

  if (closeBtn) {
    closeBtn.addEventListener("click", function () {
      sidebar.classList.remove("active");
    });
  }

  // Pastikan sidebar tertutup saat pertama kali di layar kecil
  if (window.innerWidth <= 768) {
    sidebar.classList.remove("active");
  }
});

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const password = document.querySelector('input[name="password"]');
    const confirm = document.querySelector('input[name="password_confirmation"]');

    // buat elemen error (langsung di bawah input)
    const passwordError = document.createElement("small");
    passwordError.classList.add("text-danger", "d-block", "mt-1");
    password.parentNode.appendChild(passwordError);

    const confirmError = document.createElement("small");
    confirmError.classList.add("text-danger", "d-block", "mt-1");
    confirm.parentNode.appendChild(confirmError);

    // fungsi untuk cek password saat diketik
    function validatePassword() {
        passwordError.textContent = "";
        confirmError.textContent = "";

        if (password.value.trim() !== "") {
            if (password.value.length < 6) {
                passwordError.textContent = "Password minimal 6 karakter.";
            }

            if (confirm.value.trim() !== "" && password.value !== confirm.value) {
                confirmError.textContent = "Konfirmasi password tidak cocok.";
            }
        }
    }

    // realtime listener
    password.addEventListener("input", validatePassword);
    confirm.addEventListener("input", validatePassword);

    // saat submit dicek lagi
    form.addEventListener("submit", function (e) {
        validatePassword();

        if (passwordError.textContent !== "" || confirmError.textContent !== "") {
            e.preventDefault(); // cegah kirim form
        }
    });
});
</script>




</body>
</html>
