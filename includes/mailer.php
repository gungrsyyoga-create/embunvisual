<?php
/**
 * includes/mailer.php
 * ========================
 * Konfigurasi PHPMailer untuk Gmail SMTP
 * Ganti GMAIL_USER dan GMAIL_APP_PASS dengan milik kamu
 */

// ── CONFIG — GANTI BAGIAN INI
define('GMAIL_USER',     'gungrsyworkakun@gmail.com');   // << email pengirim
define('GMAIL_APP_PASS', 'rbly antb gjso jheo');   // << App Password 16 digit
define('MAIL_FROM_NAME', 'Embun Visual');

// ── Load PHPMailer classes
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Kirim email menggunakan SMTP Gmail
 * @param string $to_email  Alamat penerima
 * @param string $to_name   Nama penerima
 * @param string $subject   Subjek email
 * @param string $body_html Isi email (HTML)
 * @return bool|string true jika berhasil, pesan error jika gagal
 */
function kirimEmail($to_email, $to_name, $subject, $body_html) {
    $mail = new PHPMailer(true);
    try {
        // Server SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = GMAIL_USER;
        $mail->Password   = GMAIL_APP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Pengirim & Penerima
        $mail->setFrom(GMAIL_USER, MAIL_FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        $mail->addReplyTo(GMAIL_USER, MAIL_FROM_NAME);

        // Konten
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body_html;
        $mail->AltBody = strip_tags($body_html);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

/**
 * Template email: Pembayaran Dikonfirmasi (Lunas)
 */
function emailLunas($nama, $invoice, $tema, $tgl_acara, $total, $checkout_url) {
    $tgl_fmt = date('d F Y', strtotime($tgl_acara));
    $total_fmt = 'Rp ' . number_format($total, 0, ',', '.');
    return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;background:#f4f4f0;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 20px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);max-width:100%;">
      <!-- Header -->
      <tr><td style="background:#1a2e1a;padding:40px 40px 30px;text-align:center;">
        <div style="font-size:28px;color:#c9a66b;margin-bottom:6px;">✦ Embun Visual</div>
        <div style="color:rgba(255,255,255,0.7);font-size:13px;letter-spacing:2px;text-transform:uppercase;">Konfirmasi Pembayaran</div>
      </td></tr>
      <!-- Body -->
      <tr><td style="padding:40px;">
        <p style="color:#555;margin:0 0 20px;">Halo, <strong style="color:#1a2e1a;">$nama</strong> 👋</p>
        <p style="color:#555;line-height:1.7;margin:0 0 30px;">Pembayaran Anda telah kami terima dan dikonfirmasi. Tim kami akan segera mulai mempersiapkan undangan digital Anda. ✅</p>
        <!-- Detail Box -->
        <table width="100%" style="background:#f9f8f6;border-radius:12px;border:1px solid #e5e2dc;margin-bottom:30px;" cellpadding="20" cellspacing="0">
          <tr><td>
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr><td style="color:#888;font-size:13px;padding-bottom:10px;">No. Invoice</td><td style="text-align:right;font-weight:700;color:#1a2e1a;">$invoice</td></tr>
              <tr><td style="color:#888;font-size:13px;padding-bottom:10px;">Koleksi Tema</td><td style="text-align:right;color:#333;">$tema</td></tr>
              <tr><td style="color:#888;font-size:13px;padding-bottom:10px;">Tanggal Acara</td><td style="text-align:right;color:#333;">$tgl_fmt</td></tr>
              <tr><td colspan="2" style="border-top:1px dashed #ddd;padding-top:15px;"></td></tr>
              <tr><td style="font-family:Georgia,serif;font-style:italic;font-size:18px;color:#1a2e1a;">Total Dibayar</td><td style="text-align:right;font-family:Georgia,serif;font-style:italic;font-size:20px;color:#c9a66b;font-weight:bold;">$total_fmt</td></tr>
            </table>
          </td></tr>
        </table>
        <p style="color:#555;line-height:1.7;margin:0 0 25px;">Hasil undangan Anda akan kami kirimkan melalui email ini setelah selesai dibuat. Pantau terus ya!</p>
        <div style="text-align:center;margin-bottom:30px;">
          <a href="$checkout_url" style="background:#1a2e1a;color:#fff;padding:14px 32px;border-radius:50px;text-decoration:none;font-size:14px;letter-spacing:1px;">📄 Lihat Invoice</a>
        </div>
        <hr style="border:none;border-top:1px solid #e5e2dc;margin:30px 0;">
        <p style="color:#aaa;font-size:12px;text-align:center;margin:0;">Embun Visual &mdash; Undangan Digital Pernikahan Elegan<br>Email ini dikirim otomatis, mohon tidak membalas.</p>
      </td></tr>
    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
}

/**
 * Template email: Proyek Selesai
 */
function emailSelesai($nama, $invoice, $tema, $link_undangan = '') {
    $link_btn = $link_undangan
        ? "<div style='text-align:center;margin-bottom:30px;'><a href='$link_undangan' style='background:#c9a66b;color:#fff;padding:14px 32px;border-radius:50px;text-decoration:none;font-size:14px;letter-spacing:1px;'>🎉 Buka Undangan Digital Saya</a></div>"
        : "<p style='color:#555;text-align:center;'>Link undangan akan kami kirimkan segera melalui WhatsApp.</p>";
    return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;background:#f4f4f0;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 20px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);max-width:100%;">
      <!-- Header -->
      <tr><td style="background:linear-gradient(135deg,#1a2e1a,#2d4a2d);padding:40px;text-align:center;">
        <div style="font-size:48px;margin-bottom:10px;">🎊</div>
        <div style="font-size:28px;color:#c9a66b;margin-bottom:6px;">Undangan Selesai!</div>
        <div style="color:rgba(255,255,255,0.7);font-size:13px;letter-spacing:2px;text-transform:uppercase;">Proyek Anda Telah Rampung</div>
      </td></tr>
      <!-- Body -->
      <tr><td style="padding:40px;">
        <p style="color:#555;margin:0 0 20px;">Halo, <strong style="color:#1a2e1a;">$nama</strong> 🎉</p>
        <p style="color:#555;line-height:1.7;margin:0 0 25px;">Kabar baik! Undangan digital Anda dengan koleksi <strong>$tema</strong> telah selesai dibuat oleh tim Embun Visual. Selamat atas momen istimewa Anda! 💍</p>
        $link_btn
        <div style="background:#f9f8f6;border-radius:12px;border-left:4px solid #c9a66b;padding:20px;margin-bottom:25px;">
          <p style="margin:0;color:#888;font-size:13px;">No. Invoice</p>
          <p style="margin:5px 0 0;font-weight:700;color:#1a2e1a;">$invoice</p>
        </div>
        <p style="color:#555;line-height:1.7;margin:0 0 20px;">Jika ada pertanyaan atau permintaan revisi, jangan ragu untuk menghubungi kami melalui WhatsApp. Kami siap membantu! 🌿</p>
        <hr style="border:none;border-top:1px solid #e5e2dc;margin:30px 0;">
        <p style="color:#aaa;font-size:12px;text-align:center;margin:0;">Embun Visual &mdash; Undangan Digital Pernikahan Elegan<br>Email ini dikirim otomatis, mohon tidak membalas.</p>
      </td></tr>
    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
}
?>
