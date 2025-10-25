<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Test Kamera</title>
</head>
<body>
  <h3>Arahkan QR ke kamera</h3>
  <video id="preview" width="400" height="300" autoplay></video>

  <script>
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        document.getElementById('preview').srcObject = stream;
      })
      .catch(err => {
        alert('Gagal akses kamera: ' + err.message);
        console.error(err);
      });
  </script>
</body>
</html>
