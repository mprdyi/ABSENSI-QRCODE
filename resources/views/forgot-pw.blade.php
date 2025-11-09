<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password | SMAN 9 Cirebon</title>
<style>
  * {margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
  body {height:100vh;display:flex;justify-content:center;align-items:center;background:linear-gradient(135deg,#1E40AF,#3B82F6);}
  .glass-card {background: rgba(255,255,255,0.15);backdrop-filter: blur(10px);border-radius:20px;padding:40px;width:90%;max-width:400px;color:#fff;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.2);}
  h2 {font-size:2rem;margin-bottom:15px;}
  p {opacity:0.9;margin-bottom:20px;}
  input {width:100%;padding:12px 15px;margin-bottom:20px;border-radius:10px;border:none;outline:none;}
  input[type=email] {background: rgba(255,255,255,0.3); color:#000;}
  button {width:100%;padding:12px;background:#2563EB;border:none;border-radius:10px;color:white;font-weight:600;cursor:pointer;transition:0.3s;}
  button:hover {background:#1E40AF;}
  .alert {padding:12px;margin-bottom:15px;border-radius:10px;}
  .alert-danger {background: rgba(255,77,79,0.1); color: #b71c1c;}
  .alert-success {background: rgba(34,197,94,0.1); color:#065f46;}
  a {color:#fff;text-decoration:none; font-size:14px;}
</style>
</head>
<body>
<div class="glass-card">
  <h2>Forgot Password</h2>
  <p>Enter your email to receive reset link</p>
  @if(session('status'))
    <div class="alert" style="color:white; border:1px solid white">{{ session('status') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" placeholder="Your Email" required>
    <button type="submit">Send Reset Link</button>
  </form>
  <div style="margin-top:15px;"><a href="{{ route('login') }}">Back to Login</a></div>
</div>
</body>
</html>
