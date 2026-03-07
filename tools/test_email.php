<?php
/**
 * tools/test_email.php
 * Hanya bisa diakses dari localhost
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$allowed_ips = ['127.0.0.1', '::1'];
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    die('<h3 style="color:red">403 - Localhost only</h3>');
}

include '../config.php';

$status = '';
$error  = '';

// ── Test 1: PHPMailer bisa dimuat?
$mailer_path = __DIR__ . '/../includes/mailer.php';
if (!file_exists($mailer_path)) {
    $error = "❌ File includes/mailer.php TIDAK DITEMUKAN di: $mailer_path";
} else {
    require_once $mailer_path;

    // ── Test 2: Cek GMAIL_USER dan GMAIL_APP_PASS
    if (GMAIL_USER === 'emailkamu@gmail.com' || empty(GMAIL_USER)) {
        $error = "❌ GMAIL_USER belum diganti di includes/mailer.php";
    } elseif (strpos(GMAIL_APP_PASS, 'xxxx') !== false) {
        $error = "❌ GMAIL_APP_PASS masih placeholder. Isi App Password 16 digit.";
    } else {
        // ── Test 3: Kirim email test
        $target_email = $_GET['to'] ?? GMAIL_USER; // Default kirim ke diri sendiri
        $result = kirimEmail(
            $target_email,
            'Test User',
            '✅ Test Email — Embun Visual',
            '<h2>Email berfungsi!</h2><p>Ini adalah email test dari sistem Embun Visual.</p><p>Waktu: ' . date('Y-m-d H:i:s') . '</p>'
        );
        if ($result === true) {
            $status = "✅ Email BERHASIL dikirim ke: <b>$target_email</b>";
        } else {
            $error = "❌ GAGAL: $result";
        }
    }
}

// ── Test 4: Cek email_pemesan di DB
$q = mysqli_query($conn, "SELECT id, invoice, nama_pemesan, email_pemesan, status_pembayaran FROM pesanan ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Email Test</title>
<style>body{font-family:sans-serif;padding:30px;background:#f1f5f9} .card{background:#fff;padding:20px;border-radius:8px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.07)} .ok{color:#166534;background:#f0fdf4;padding:12px;border-radius:6px;border-left:4px solid #22c55e} .err{color:#991b1b;background:#fee2e2;padding:12px;border-radius:6px;border-left:4px solid #ef4444} table{width:100%;border-collapse:collapse} th,td{padding:10px;border:1px solid #e2e8f0;font-size:.875rem} th{background:#f8fafc}</style>
</head>
<body>
<h2>🛠️ Debug Email — Embun Visual</h2>

<div class="card">
<h3>SMTP Config</h3>
<table><tr><th>Key</th><th>Value</th></tr>
<tr><td>GMAIL_USER</td><td><?= defined('GMAIL_USER') ? GMAIL_USER : '<em>belum define</em>' ?></td></tr>
<tr><td>GMAIL_APP_PASS</td><td><?= defined('GMAIL_APP_PASS') ? str_repeat('*', strlen(GMAIL_APP_PASS)) . ' (' . strlen(GMAIL_APP_PASS) . ' karakter)' : '<em>belum define</em>' ?></td></tr>
<tr><td>MAIL_FROM_NAME</td><td><?= defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : '<em>belum define</em>' ?></td></tr>
<tr><td>PHPMailer src/</td><td><?= file_exists(__DIR__ . '/../includes/PHPMailer/PHPMailer.php') ? '✅ Ditemukan' : '❌ TIDAK ADA' ?></td></tr>
</table>
</div>

<div class="card">
<h3>Kirim Email Test</h3>
<?php if ($status): ?><div class="ok"><?= $status ?></div><?php endif; ?>
<?php if ($error):  ?><div class="err"><?= $error ?></div><?php endif; ?>
<?php if (!$error && !$status): ?>
<form style="margin-top:15px">
    <label>Kirim ke Email:</label><br>
    <input type="email" name="to" value="<?= htmlspecialchars($_GET['to'] ?? GMAIL_USER) ?>" style="padding:8px;width:300px;border:1px solid #ccc;border-radius:4px;margin:8px 0">
    <button type="submit" style="padding:8px 20px;background:#1a2e1a;color:#fff;border:none;border-radius:4px;cursor:pointer">Kirim Test</button>
</form>
<?php endif; ?>
<?php if (!$error): ?>
<form style="margin-top:10px">
    <label>Kirim ke Email:</label><br>
    <input type="email" name="to" value="<?= htmlspecialchars($_GET['to'] ?? (defined('GMAIL_USER') ? GMAIL_USER : '')) ?>" style="padding:8px;width:300px;border:1px solid #ccc;border-radius:4px;margin:8px 0">
    <button type="submit" style="padding:8px 20px;background:#1a2e1a;color:#fff;border:none;border-radius:4px;cursor:pointer">Kirim Test</button>
</form>
<?php endif; ?>
</div>

<div class="card">
<h3>5 Pesanan Terbaru (cek email_pemesan)</h3>
<table>
<tr><th>ID</th><th>Invoice</th><th>Nama</th><th>email_pemesan</th><th>Status Bayar</th></tr>
<?php while($row = mysqli_fetch_assoc($q)): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['invoice'] ?></td>
    <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
    <td><?= !empty($row['email_pemesan']) ? "<span style='color:green'>✅ ".$row['email_pemesan']."</span>" : "<span style='color:red'>❌ Kosong</span>" ?></td>
    <td><?= $row['status_pembayaran'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

</body>
</html>
