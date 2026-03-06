<?php
include 'config.php';

// Ambil nomor invoice dari URL
if (!isset($_GET['inv'])) {
    die("Invoice tidak valid.");
}

$invoice = mysqli_real_escape_string($conn, $_GET['inv']);

// Cari data pesanan beserta nama temanya
$query = mysqli_query($conn, "SELECT p.*, k.nama_tema, k.kategori FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id WHERE p.invoice = '$invoice'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Pesanan tidak ditemukan.");
}

$tanggal_pesanan = date('d F Y');
if(isset($data['created_at']) && $data['created_at'] != "" && $data['created_at'] != null) {
    $tanggal_pesanan = date('d F Y', strtotime($data['created_at']));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo $invoice; ?> | Embun Visual</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #121413;
            --text-main: #1A1A1A;
            --text-muted: #555555;
            --gold: #C9A66B;
            --border: #DDDDDD;
            --surface: #FFFFFF;
            --bg: #F4F6F9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            padding: 40px 20px;
        }

        .invoice-wrapper {
            max-width: 850px;
            margin: 0 auto;
            background: var(--surface);
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        .inv-header {
            padding: 50px 60px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary);
        }

        .brand-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 5px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .brand-section p {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .inv-details {
            text-align: right;
        }
        .inv-details h2 {
            font-size: 2.5rem;
            color: var(--border);
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .inv-info-table {
            border-collapse: collapse;
            float: right;
        }
        .inv-info-table td {
            padding: 3px 0 3px 20px;
            font-size: 0.9rem;
        }
        .inv-info-table td:first-child {
            color: var(--text-muted);
            font-weight: 500;
            text-align: right;
        }
        .inv-info-table td:last-child {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
            color: var(--text-main);
        }

        .inv-body {
            padding: 40px 60px;
        }

        .billing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }
        .billing-box h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 5px;
        }
        .billing-box p {
            font-size: 1.05rem;
            margin-bottom: 3px;
            font-weight: 500;
        }
        .billing-box span {
            display: block;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .items-table th {
            text-align: left;
            padding: 15px;
            background-color: #F8F9FA;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
        }
        .items-table td {
            padding: 20px 15px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }
        .item-name {
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--primary);
            margin-bottom: 5px;
        }
        .item-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        .summary-box {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }
        .summary-box td {
            padding: 12px 15px;
            font-size: 0.95rem;
        }
        .summary-box tr:not(.total-row) td {
            border-bottom: 1px solid #EEE;
        }
        .summary-box td:first-child {
            color: var(--text-muted);
        }
        .summary-box td:last-child {
            text-align: right;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
        }
        .total-row td {
            background-color: rgba(201, 166, 107, 0.1);
            color: var(--primary) !important;
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 2px solid var(--primary);
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .inv-footer {
            padding: 40px 60px;
            background-color: #F8F9FA;
            border-top: 1px solid var(--border);
            margin-top: 40px;
        }
        .notes h4 {
            font-size: 0.9rem;
            color: var(--primary);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .notes p {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        .status-stamp {
            display: inline-block;
            padding: 8px 25px;
            border: 2px solid;
            border-radius: 4px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 1.2rem;
            transform: rotate(-5deg);
            opacity: 0.8;
            margin-top: 20px;
        }
        .stamp-pending { color: #D97706; border-color: #D97706; }
        .stamp-paid { color: #059669; border-color: #059669; }

        .action-bar {
            max-width: 850px;
            margin: 0 auto 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        .btn {
            padding: 12px 25px;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .btn-print {
            background: var(--surface);
            color: var(--primary);
            border: 1px solid var(--border);
        }
        .btn-print:hover { border-color: var(--primary); background: #F8F9FA; }
        .btn-pay {
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }
        .btn-pay:hover { background: transparent; color: var(--primary); }

        @media print {
            body { background: white; padding: 0; }
            .invoice-wrapper { box-shadow: none; border: none; max-width: 100%; }
            .action-bar { display: none !important; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="checkout.php?inv=<?php echo $invoice; ?>" class="btn btn-pay"><i class="fas fa-credit-card"></i> Kembali & Bayar</a>
        <button onclick="window.print()" class="btn btn-print"><i class="fas fa-print"></i> Cetak Dokumen</button>
    </div>

    <div class="invoice-wrapper">
        <div class="inv-header">
            <div class="brand-section">
                <div class="brand-logo" style="margin-bottom: 12px; display: flex; align-items: center;">
                    <img src="assets/logo.png" alt="Embun Visual Logo" style="height: 45px;" onerror="this.onerror=null; this.outerHTML='<h1><i class=\'fas fa-leaf\' style=\'color: var(--gold); margin-right: 8px;\'></i> Embun Visual</h1>'">
                </div>
                <!-- <h1>Embun Visual</h1> -->
                <p>Digital Wedding Invitation Premium<br>www.embunvisual.com<br>WhatsApp: +62 812-3456-7890</p>
            </div>
            <div class="inv-details">
                <h2>INVOICE</h2>
                <table class="inv-info-table">
                    <tr>
                        <td>Nomor Invoice:</td>
                        <td><?php echo $invoice; ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Terbit:</td>
                        <td><?php echo $tanggal_pesanan; ?></td>
                    </tr>
                    <tr>
                        <td>Jatuh Tempo:</td>
                        <td>
                            <?php 
                            // Jatuh tempo misal 1 hari setelah pesanan
                            $date = new DateTime($tanggal_pesanan);
                            $date->modify('+1 day');
                            echo $date->format('d F Y');
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="inv-body">
            <div class="billing-grid">
                <div class="billing-box">
                    <h3>Ditagihkan Kepada:</h3>
                    <p><?php echo $data['nama_pemesan']; ?></p>
                    <span><?php echo $data['no_whatsapp']; ?></span>
                    <span>Tanggal Acara: <?php echo date('d F Y', strtotime($data['tanggal_acara'])); ?></span>
                </div>
                <!-- <div class="billing-box" style="text-align: right;">-->
                    <!-- Placeholder Jika ada detail tambahan client di masa depan -->
                <!--</div>-->
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="50%">Deskripsi Layanan</th>
                        <th width="15%" class="text-center">Kuantitas</th>
                        <th width="30%" class="text-right">Total (IDR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <div class="item-name"><?php echo $data['nama_tema']; ?></div>
                            <div class="item-desc">Desain Template <?php echo isset($data['kategori']) ? $data['kategori'] : 'Undangan Web'; ?> Eksklusif (Termasuk Setup, Revisi minor, Fitur Navigasi, RSVP, Galeri).</div>
                        </td>
                        <td class="text-center">1 Paket</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">
                            <?php echo number_format($data['total_tagihan'],0,',','.'); ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="clearfix">
                <table class="summary-box">
                    <tr>
                        <td>Subtotal</td>
                        <td><?php echo number_format($data['total_tagihan'],0,',','.'); ?></td>
                    </tr>
                    <tr>
                        <td>Pajak (0%)</td>
                        <td>0</td>
                    </tr>
                    <tr class="total-row">
                        <td>TOTAL TAGIHAN</td>
                        <td>Rp <?php echo number_format($data['total_tagihan'],0,',','.'); ?></td>
                    </tr>
                </table>
            </div>
            
            <?php 
            $status_class = (strpos(strtolower($data['status_pembayaran']), 'lunas') !== false) ? 'stamp-paid' : 'stamp-pending';
            ?>
            <div class="status-stamp <?php echo $status_class; ?>">
                <?php echo strtoupper($data['status_pembayaran']); ?>
            </div>
            
        </div>

        <div class="inv-footer">
            <div class="notes">
                <h4>Informasi Pembayaran & Syarat Ketentuan</h4>
                <p>1. Transaksi dilakukan ke Bank BCA: <b>1234567890</b> (a.n PT Embun Visual Indonesia) atau E-Wallet: <b>081234567890</b>.<br>
                   2. Pengerjaan undangan digital akan dimulai setelah pembayaran penuh (100%) kami terima & konfirmasi.<br>
                   3. Harap sertakan Nomor Invoice pada berita transfer atau lampirkan invoice ini bersama bukti pembayaran Anda.<br>
                   4. Untuk pertanyaan terkait tagihan, silakan hubungi tim Support melalui WhatsApp.</p>
            </div>
        </div>
    </div>

</body>
</html>
