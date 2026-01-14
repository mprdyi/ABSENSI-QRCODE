<?php
require __DIR__ . '/vendor/autoload.php';

$client = new Google\Client();

$client->setClientId('930883551669-qf1tvink5e8gth8om1ie9sq1hfpj4fif.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:8080/');

$client->setAccessType('offline');
$client->setPrompt('consent');
$client->addScope(Google\Service\Drive::DRIVE);

// === STEP 1: Dapatkan URL Login ===
$authUrl = $client->createAuthUrl();

echo "BUKA URL INI:\n$authUrl\n\n";
echo "SESUDAH LOGIN, COPY PASTE URL REDIRECT DI BROWSER KE SINI\n";
echo "Contoh: http://localhost:8080/?code=xxxx\n\n";
echo "Paste URL redirect di sini: ";

$redirectUrl = trim(fgets(STDIN));

// Ambil code=xxx dari URL
parse_str(parse_url($redirectUrl, PHP_URL_QUERY), $params);

if (!isset($params['code'])) {
    echo "Error: 'code' tidak ditemukan!\n";
    exit;
}

$authCode = $params['code'];

// === STEP 2: Tukar code jadi token ===
$token = $client->fetchAccessTokenWithAuthCode($authCode);

// === STEP 3: Cetak Refresh Token ===
echo "\n=== TOKEN ===\n";
print_r($token);

// Simpan ke file
file_put_contents('token.json', json_encode($token));

echo "\nRefresh token disimpan ke token.json\n";
