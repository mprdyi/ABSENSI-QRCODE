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

    .left {
      flex: 1;
      background: #1E40AF; /* biru solid */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      padding: 50px;
    }

    .left h1 {
      font-size: 2.2rem;
      margin-bottom: 10px;
    }

    .left p {
      opacity: 0.9;
      font-weight: 300;
      text-align: center;
    }

    .left button {
      background: white;
      color: #1E40AF;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      margin-top: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .left button:hover {
      background: #e5e7eb;
    }

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

    @media (max-width: 850px) {
      body {
        flex-direction: column;
      }
      .left, .right {
        flex: none;
        width: 100%;
        height: 50vh;
      }
    }
    .custom-alert {
    background-color: #f8d7da; /* merah muda lembut */
    color: #842029; /* teks merah gelap */
    border: 1px solid #f5c2c7; /* border merah muda */
    padding: 10px 15px;
    border-radius: 5px;
    margin-top: 10px;
    margin-bottom:30px;
    font-size: 0.9rem;
    text-align:center;
}

  </style>
</head>
<body>
  <div class="left">
    <h1>ABSENSI NEGLAN</h1>
    <p>Welcome back. You're just one step away from your dashboard.</p>
    <button>Sign Up</button>
  </div>

  <div class="right">
    <div class="login-box">
      <h2>Sign In</h2>
      @if ($errors->any())
        <div class="custom-alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="input-group">
          <label>Username</label>
          <input type="email" placeholder="Username" name="email">
          @error('password')
            <span style="color:red; font-size:0.85rem">{{ $message }}</span>
        @enderror
        </div>
        <div class="input-group">
          <label>Password</label>
          <input type="password" placeholder="Password" name="password">
          @error('password')
            <span style="color:red; font-size:0.85rem">{{ $message }}</span>
        @enderror
        </div>
        <button class="btn-login">Sign in</button>
        <div class="extra">
          <a href="#">Forgot Password?</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
