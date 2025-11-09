<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | NegLan</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
    }

    /* === LEFT PANEL === */
    .left {
      flex: 1;
      background: linear-gradient(135deg, #1E40AF 0%, #1D4ED8 60%, #3B82F6 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      padding: 50px;
    }

    .glass-box {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 40px;
      text-align: center;
      max-width: 420px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    .logo {
      font-size: 3.2rem;
      margin-bottom: 20px;
      color: #fff;
      text-shadow: 0 4px 15px rgba(255,255,255,0.3);
    }

    .glass-box h1 {
      font-size: 2rem;
      margin-bottom: 10px;
      letter-spacing: 1px;
    }

    .glass-box p {
      opacity: 0.9;
      font-weight: 300;
      margin-bottom: 25px;
      line-height: 1.5;
    }

    .features {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 20px;
      text-align: left;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.95rem;
      opacity: 0.95;
    }

    .feature-item i {
      color: #fff;
      background: rgba(255,255,255,0.2);
      padding: 8px;
      border-radius: 10px;
    }

    /* === RIGHT PANEL === */
    .right {
      flex: 1;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .login-box {
      width: 80%;
      max-width: 380px;
    }

    .login-box h2 {
      color: #1E3A8A;
      margin-bottom: 25px;
      font-size: 1.8rem;
    }

    .input-group {
      margin-bottom: 20px;
    }

    .input-group label {
      font-size: 0.9rem;
      color: #1E293B;
      display: block;
      margin-bottom: 5px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 15px;
      border-radius: 8px;
      border: 1.5px solid #CBD5E1;
      font-size: 0.95rem;
      transition: 0.2s;
    }

    .input-group input:focus {
      border-color: #2563EB;
      outline: none;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    .btn-login {
      width: 100%;
      padding: 12px;
      background: #2563EB;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
    }

    .btn-login:hover {
      background: #1E40AF;
    }

    .extra {
      margin-top: 10px;
      font-size: 0.85rem;
      color: #475569;
      text-align: right;
    }

    .extra a {
      color: #2563EB;
      text-decoration: none;
    }

    .extra a:hover {
      text-decoration: underline;
    }

    .custom-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 77, 79, 0.1);
    border: 1px solid rgba(255, 77, 79, 0.3);
    color: #b71c1c;
    padding: 14px 18px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(255, 0, 0, 0.08);
    backdrop-filter: blur(6px);
    margin-bottom:8px;
    transition: all 0.3s ease;
    max-width: 400px;
  }
  .custom-alert:hover {
    background: rgba(255, 77, 79, 0.15);
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(255, 0, 0, 0.1);
  }


    /* === RESPONSIVE === */
    @media (max-width: 850px) {
      body {
        flex-direction: column;
        height: auto;
      }
      .left, .right {
        width: 100%;
        height: auto;
        padding: 40px 20px;
      }
      .glass-box {
        max-width: 90%;
        padding: 30px 25px;
      }
    }

    /* HP: ubah deskripsi + sembunyikan fitur */
    @media (max-width: 800px) {
      .features {
        display: none;
      }
      .glass-box p {
        font-weight: 600;
        font-size: 1rem;
        opacity: 1;
      }
    }

    @media (max-width: 400px) {
      .glass-box {
        padding: 20px 15px;
      }
      .glass-box h1 {
        font-size: 1.3rem;
      }
      .logo {
        font-size: 2.5rem;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="left">
    <div class="glass-box" id="gb">
      <div class="logo">
        <i class="fa-solid fa-graduation-cap"></i>
      </div>
      <h1 id="judul">ABSENSI NEGLAN</h1>
      <p id="desc">
        Aplikasi monitoring Absensi keterlambatan siswa dan management Administrasi guru piket
      </p>

      <div class="features">
        <div class="feature-item">
          <i class="fa-solid fa-fingerprint"></i>
          <span>Absensi Otomatis & Cepat</span>
        </div>
        <div class="feature-item">
          <i class="fa-solid fa-chart-line"></i>
          <span>Surat Izin Kelas</span>
        </div>
        <div class="feature-item">
          <i class="fa-solid fa-users-gear"></i>
          <span>Rekap Data Real-Time</span>
        </div>
      </div>
    </div>
  </div>

  <div class="right">
    <div class="login-box">
      <h2>Sign In</h2>
      @if ($errors->any()) <div class="custom-alert"> {{ $errors->first() }} </div> @endif
      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="input-group">
          <label>Username</label>
          <input type="email" placeholder="Username" name="email">
        </div>
        <div class="input-group">
          <label>Password</label>
          <input type="password" placeholder="Password" name="password">
        </div>
        <button class="btn-login">Sign in</button>
        <div class="extra">
          <a href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // ganti teks deskripsi saat di layar kecil
    function updateDescription() {
      const desc = document.getElementById('desc');
      var judul = document.getElementById('judul');
      var glassBox = document.getElementById('gb');
      if (window.innerWidth <= 800) {
        desc.textContent = "SMA Negeri 9 Cirebon";
        judul.style.fontSize ="20px";
        glassBox.style.width ="100%";
        glassBox.style.paddingTop ="10px";
        glassBox.style.paddingBottom ="0px";


      } else {
        desc.textContent = " Aplikasi monitoring Absensi keterlambatan siswa dan management Administrasi guru piket";
      }
    }
    updateDescription();
    window.addEventListener('resize', updateDescription);
  </script>
</body>
</html>
