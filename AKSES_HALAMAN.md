# 🔑 AKSES HALAMAN - PANDUAN LENGKAP
**Embun Visual - Cara Mengakses Setiap Halaman**

---

## 🏠 HOMEPAGE / MAIN SITE

### Cara Akses:
```
URL: http://localhost/embunvisual/
atau
URL: http://localhost/embunvisual/index.php
```

### Deskripsi:
- Landing page utama
- Showcase tema & fitur
- Catalog koleksi
- Gallery fotos
- Contact form

### File Location:
```
📁 Root: /embunvisual/index.php
```

---

## 🔐 LOGIN PAGE

### Cara Akses:
```
URL: http://localhost/embunvisual/public/login.php
atau
URL: http://localhost/embunvisual/login.php (via .htaccess)
```

### Deskripsi:
- Login untuk user/admin
- Form email & password
- Remember me option
- Reset password link

### File Location:
```
📁 Public Folder: /embunvisual/public/login.php
```

---

## 👨‍💼 ADMIN DASHBOARD

### Cara Akses (3 Metode):

#### Metode 1: Via Root Entry Point (RECOMMENDED)
```
URL: http://localhost/embunvisual/admin.php
```

#### Metode 2: Direct Admin Folder
```
URL: http://localhost/embunvisual/admin/admin.php
```

#### Metode 3: Via .htaccess Shortcut
```
URL: http://localhost/embunvisual/admin/
(dengan trailing slash)
```

### Requirement:
- ✅ Harus sudah login
- ✅ Role harus: Super Admin atau Staff

### Deskripsi:
- Dashboard Admin
- Kelola Klien
- Kelola Template
- View Reports
- Manage Users

### File Location:
```
📁 Admin Folder: /embunvisual/admin/admin.php
📁 Entry Point: /embunvisual/admin.php (auto-redirect)
```

---

## 📊 ETIKET DASHBOARD

### Cara Akses (3 Metode):

#### Metode 1: Direct File
```
URL: http://localhost/embunvisual/admin/admin_etiket_dashboard.php
```

#### Metode 2: Via .htaccess
```
URL: http://localhost/embunvisual/etiket.php
```

#### Metode 3: Via Shortcut
```
URL: http://localhost/embunvisual/etiket/
```

### Requirement:
- ✅ Harus sudah login
- ✅ Role harus: Super Admin atau Staff

### Deskripsi:
- Manage Etiket
- View Etiket Status
- Generate Etiket
- Export Data

### File Location:
```
📁 Admin Folder: /embunvisual/admin/admin_etiket_dashboard.php
```

---

## 💳 INVOICE PAGE

### Cara Akses:
```
URL: http://localhost/embunvisual/public/invoice.php
atau
URL: http://localhost/embunvisual/invoice.php (via .htaccess)
```

### Deskripsi:
- View Invoice Detail
- Download Invoice
- Print Invoice

### File Location:
```
📁 Public Folder: /embunvisual/public/invoice.php
```

---

## 🛒 CHECKOUT PAGE

### Cara Akses:
```
URL: http://localhost/embunvisual/public/checkout.php
atau
URL: http://localhost/embunvisual/checkout.php (via .htaccess)
```

### Deskripsi:
- Order Processing
- Payment Method Selection
- Order Summary
- Confirmation

### File Location:
```
📁 Public Folder: /embunvisual/public/checkout.php
```

---

## 🎁 INVITATION PAGES

### Cara Akses:

#### Basic Invitation:
```
URL: http://localhost/embunvisual/undangan/basic/[client-name]/index.php?pid=[id]
```

#### Premium Invitation:
```
URL: http://localhost/embunvisual/undangan/premium/[client-name]/index.php?pid=[id]
```

#### Exclusive Invitation:
```
URL: http://localhost/embunvisual/undangan/exclusive/[client-name]/index.php?pid=[id]&mode=invitation
```

### Parameters:
- `[client-name]` = Nama klien (contoh: "pt-event-organizer")
- `[id]` = Pesanan ID
- `mode` = Untuk Exclusive: invitation, barcode, atau rsvp

### File Location:
```
📁 Undangan Folder: /embunvisual/undangan/[tier]/[client-name]/index.php
```

---

## 🔌 API ENDPOINTS

### Cara Akses:

#### RSVP API:
```
POST: http://localhost/embunvisual/app/api/api_rsvp.php
```

#### Etiket API:
```
POST: http://localhost/embunvisual/app/api/api_etiket.php
```

#### Notification API:
```
POST: http://localhost/embunvisual/app/api/api_notif.php
```

#### Status Update API:
```
POST: http://localhost/embunvisual/app/api/api_update_status.php
```

#### Barcode Scan API:
```
POST: http://localhost/embunvisual/admin_premium/api/api_barcode_scan.php
```

### File Location:
```
📁 API Folder: /embunvisual/app/api/
```

---

## 📱 PREMIUM ADMIN (Legacy)

### Cara Akses:
```
URL: http://localhost/embunvisual/admin_premium/login.php
```

### Deskripsi:
- Premium Admin Login
- Premium Client Dashboard
- Chat Interface
- Barcode Management

### File Location:
```
📁 Premium Admin: /embunvisual/admin_premium/
```

---

## 🔄 AKSES MELALUI .HTACCESS (Friendly URLs)

Konfigurasi `.htaccess` memudahkan akses:

| Akses Friendy | Actual File |
|---------------|-------------|
| `/` | `/index.php` |
| `/admin.php` | `/admin/admin.php` |
| `/admin/` | `/admin/admin.php` |
| `/etiket.php` | `/admin/admin_etiket_dashboard.php` |
| `/etiket/` | `/admin/admin_etiket_dashboard.php` |
| `/login.php` | `/public/login.php` |
| `/invoice.php` | `/public/invoice.php` |
| `/checkout.php` | `/public/checkout.php` |

---

## 📋 AKSES QUICK REFERENCE

### Halaman Publik:
```
Homepage:       http://localhost/embunvisual/
Login:          http://localhost/embunvisual/login.php
Invoice:        http://localhost/embunvisual/invoice.php
Checkout:       http://localhost/embunvisual/checkout.php
```

### Halaman Admin:
```
Admin Panel:    http://localhost/embunvisual/admin.php
                (atau: /admin/ atau /admin/admin.php)

Etiket:         http://localhost/embunvisual/etiket.php
                (atau: /etiket/ atau /admin/admin_etiket_dashboard.php)

Premium Admin:  http://localhost/embunvisual/admin_premium/login.php
```

### Undangan (Contoh):
```
Basic:          http://localhost/embunvisual/undangan/basic/nama-klien/index.php?pid=1
Premium:        http://localhost/embunvisual/undangan/premium/nama-klien/index.php?pid=2
Exclusive:      http://localhost/embunvisual/undangan/exclusive/nama-klien/index.php?pid=3&mode=invitation
```

---

## 🔒 PARAMETER AKSES

### Authentication Required:
- ✅ Admin Panel
- ✅ Etiket Dashboard
- ✅ Admin Premium

### Public Access:
- ✅ Homepage
- ✅ Login Page
- ✅ Invoice
- ✅ Checkout
- ✅ Invitations (guest link)

---

## ⚙️ KONFIGURASI AKSES

### Di `config/constants.php`:
```php
// Paths untuk akses internal
define('BASE_PATH', dirname(__FILE__) . '/..');
define('APP_PATH', BASE_PATH . '/app');
define('ADMIN_PATH', BASE_PATH . '/admin');
define('PUBLIC_PATH', BASE_PATH . '/public');
```

### Di `.htaccess`:
```apache
RewriteEngine On
RewriteBase /embunvisual/

# Redirect friendly URLs ke actual files
RewriteRule ^admin/?$ admin/admin.php [L]
RewriteRule ^etiket/?$ admin/admin_etiket_dashboard.php [L]
```

---

## 🆘 TROUBLESHOOTING AKSES

### Problem: "404 Not Found" saat akses admin
**Solusi:**
- Cek apakah `.htaccess` enabled di Apache
- Cek apakah `mod_rewrite` active
- Try: `http://localhost/embunvisual/admin/admin.php` (direct)

### Problem: ".htaccess tidak bekerja"
**Solusi:**
- Edit `httpd.conf` di Apache
- Cari `AllowOverride` dan ubah ke `All`
- Restart Apache

```apache
<Directory "C:/xampp/htdocs/embunvisual">
    AllowOverride All
</Directory>
```

### Problem: "Redirect loop" di admin
**Solusi:**
- Check login status
- Ensure bootstrap.php loaded
- Check session variable

### Problem: "Permission denied" akses admin
**Solusi:**
- Login dengan akun Super Admin
- Check role di database: `SELECT role FROM admin_users WHERE id = ?`
- Ensure role adalah "Super Admin" atau "Staff"

---

## 📊 FOLDER STRUCTURE REFERENCE

```
embunvisual/
├── index.php                    ← MAIN ENTRY POINT
├── admin.php                    ← ADMIN ENTRY POINT
├── login.php                    ← (via .htaccess)
├── public/
│   ├── login.php
│   ├── invoice.php
│   └── checkout.php
├── admin/
│   ├── admin.php                ← ACTUAL ADMIN FILE
│   └── admin_etiket_dashboard.php
├── admin_premium/
│   ├── login.php
│   ├── index.php
│   └── api/
├── app/api/
│   ├── api_rsvp.php
│   ├── api_etiket.php
│   ├── api_notif.php
│   └── api_update_status.php
└── undangan/
    ├── basic/
    ├── premium/
    └── exclusive/
```

---

## ✅ REKOMENDASI AKSES

### Untuk Users:
👉 **Homepage:** `http://localhost/embunvisual/`

### Untuk Klien:
👉 **Login:** `http://localhost/embunvisual/login.php`

### Untuk Admin:
👉 **Admin Panel:** `http://localhost/embunvisual/admin.php`

### Untuk Premium Admin:
👉 **Premium:** `http://localhost/embunvisual/admin_premium/login.php`

---

**Last Updated:** March 9, 2026
**Version:** 1.0.0
**Status:** 🟢 Production Ready
