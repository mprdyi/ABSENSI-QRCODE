<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password | SMAN 9 CIREBON</title>
<script src="{{ asset('Asset/jquery/jquery.min.js') }}"></script>
<style>
  * {margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
  body {
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#1E40AF,#3B82F6);
  }

  .glass-card {
    position: relative;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    padding: 40px;
    width: 90%;
    max-width: 400px;
    color: #fff;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    backdrop-filter: blur(10px) saturate(180%);
    -webkit-backdrop-filter: blur(10px) saturate(180%);
    border: 1px solid rgba(255,255,255,0.2);
    overflow: hidden;
  }

  .glass-card::before {
    content:"";
    position:absolute;
    top:-50%;
    left:-50%;
    width:200%;
    height:200%;
    background: linear-gradient(120deg, rgba(255,255,255,0.3), rgba(255,255,255,0));
    transform: rotate(25deg);
    pointer-events:none;
  }

  h2 {font-size:2rem;margin-bottom:15px;}
  p {opacity:0.9;margin-bottom:20px;}

  .form-group {
    margin-bottom: 15px;
    text-align: left;
  }

  input {
    width:100%;
    padding:12px 15px;
    border-radius:12px;
    border:none;
    outline:none;
    background: rgba(255,255,255,0.25);
    color:#000;
    backdrop-filter: blur(5px);
  }
  input::placeholder {color:#333;}

  .error-text {
    color: white;
    font-size: 0.85rem;
    margin-top: 4px;
    display: block;
  }

  button {
    width:100%;
    padding:12px;
    background: rgba(37,99,235,0.8);
    border:none;
    border-radius:12px;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    backdrop-filter: blur(5px);
  }
  button:hover {background: rgba(30,64,175,0.9);}

  .alert {padding:12px;margin-bottom:15px;border-radius:12px;}
  .alert-danger {background: rgba(255,77,79,0.2); color: #b71c1c;}
  .alert-success {background: rgba(34,197,94,0.2); color:#065f46;}
  a {color:#fff;text-decoration:none; font-size:14px;}
</style>
</head>
<body>

<div class="glass-card">
  <h2>Reset Password</h2>
  <p>Masukkan email dan password baru Anda</p>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
      <input type="email" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required>
      <small class="error-text"></small>
    </div>

    <div class="form-group">
      <input type="password" name="password" placeholder="Password Baru" required>
      <small class="error-text"></small>
    </div>

    <div class="form-group">
      <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
      <small class="error-text"></small>
    </div>

    <button type="submit">Reset Password</button>
  </form>

  <div style="margin-top:15px;"><a href="{{ route('login') }}">Kembali ke Login</a></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const password = document.querySelector('input[name="password"]');
    const confirm = document.querySelector('input[name="password_confirmation"]');

    const passwordError = password.nextElementSibling;
    const confirmError = confirm.nextElementSibling;

    function validatePassword() {
        passwordError.textContent = "";
        confirmError.textContent = "";

        if (password.value.trim() !== "" && password.value.length < 6) {
            passwordError.textContent = "Password minimal 6 karakter.";
        }

        if (confirm.value.trim() !== "" && password.value !== confirm.value) {
            confirmError.textContent = "Konfirmasi password tidak cocok.";
        }
    }

    password.addEventListener("input", validatePassword);
    confirm.addEventListener("input", validatePassword);

    form.addEventListener("submit", function (e) {
        validatePassword();
        if (passwordError.textContent !== "" || confirmError.textContent !== "") {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
