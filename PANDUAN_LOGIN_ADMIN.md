# 🔐 PANDUAN LOGIN ADMIN - EMBUN VISUAL
**Lengkap dengan Troubleshooting & Data Test**

---

## 🎯 Akses Login

### URL Login:
```
http://localhost/embunvisual/login.php
```

atau

```
http://localhost/embunvisual/public/login.php
```

---

## 👨‍💻 Data Login Test

Gunakan salah satu akun berikut untuk login:

### Super Admin Account:
```
Username: embeditor
Password: embeditor123
```

**Role:** Super Admin - Akses penuh ke semua fitur

---

## 📋 Form Login

### Field yang Diisi:

1. **Username**
   - Input: `embeditor` (atau username admin yang tersedia)
   - Type: Text
   - Required: Yes

2. **Password**
   - Input: `embeditor123` (password must match MD5 hash in database)
   - Type: Password
   - Required: Yes

### Submit Button:
- Click: **"MASUK" / "LOGIN"**

---

## ✅ Proses Login

### Step 1: Akses Halaman Login
```
1. Buka browser
2. Masuk ke: http://localhost/embunvisual/login.php
```

### Step 2: Isi Form Login
```
1. Username: embeditor
2. Password: embeditor123
3. Click "MASUK" button
```

### Step 3: Tunggu Redirect
```
1. System akan validasi credentials
2. Jika benar → Auto redirect ke Admin Dashboard
3. Session akan tercreate
```

### Step 4: Admin Dashboard
```
URL akan berubah ke: http://localhost/embunvisual/admin.php
```

---

## 🎛️ Session Variables Yang Tercipta

Setelah login berhasil, system akan set:

```php
$_SESSION['admin_embun'] = true;              // Flag login
$_SESSION['admin_id'] = '1';                  // User ID
$_SESSION['admin_username'] = 'embeditor';    // Username
$_SESSION['admin_role'] = 'Super Admin';      // Role/Permission
```

---

## 🔒 Password Hashing

Passwords di database disimpan dengan MD5:

```php
// Contoh:
password = "embeditor123"
MD5 hash = "dcc33ba44d7e6d0aba9d7c52e8aedb85"
```

Untuk login baru melalui database:

```sql
-- Insert new admin
INSERT INTO admin_users (username, password, role, nama_lengkap)
VALUES ('newadmin', MD5('password123'), 'Super Admin', 'Nama Admin');

-- Update password existing
UPDATE admin_users
SET password = MD5('newpassword')
WHERE username = 'embeditor';
```

---

## 🚨 Error Messages & Solusi

### Error 1: "Username atau Password salah!"

**Penyebab:**
- Username tidak ditemukan di database
- Password tidak sesuai
- Username/password typo

**Solusi:**
1. Double-check username & password
2. Pastikan CAPS LOCK tidak aktif
3. Verifikasi data di database:
   ```sql
   SELECT * FROM admin_users WHERE username = 'embeditor';
   ```

### Error 2: "CRASH DATABASE DETECTED!"

**Penyebab:**
- Koneksi database gagal
- Table `admin_users` tidak ada
- Query syntax error

**Solusi:**
1. Cek `config/constants.php` untuk database credentials
2. Pastikan MySQL running
3. Verifiy table ada:
   ```sql
   SHOW TABLES LIKE 'admin_users';
   ```

### Error 3: Timeout / Session Expired

**Penyebab:**
- Session timeout 24 jam
- Server restart
- Browser cookies cleared

**Solusi:**
1. Login ulang ke halaman login
2. Clear browser cookies (Ctrl+Shift+Delete)
3. Coba browser berbeda

### Error 4: Redirect Loop

**Penyebab:**
- Session tidak tersimpan
- Permission issue
- Path error pada redirect

**Solusi:**
1. Check `config/bootstrap.php` loaded dengan benar
2. Ensure `session.save_path` writable
3. Check folder `uploads/` permission

---

## 🏠 Setelah Login Berhasil

### Halaman Admin:
```
URL: http://localhost/embunvisual/admin.php
atau
URL: http://localhost/embunvisual/admin/admin.php
```

### Fitur Admin:
- 📊 Dashboard
- 👥 Manage Users
- 📋 Manage Orders
- 🎫 Manage Etiket
- 📁 Kelola Folder Klien
- ⚙️ Settings
- dan lainnya...

---

## 🔄 Flow Diagram

```
┌─────────────────────────────┐
│   Login Page                 │
│ /login.php                  │
└────────────┬────────────────┘
             │
             ▼
┌─────────────────────────────┐
│   Form Submission (POST)     │
│ Username + Password MD5      │
└────────────┬────────────────┘
             │
             ▼
┌─────────────────────────────┐
│   Database Query            │
│ SELECT FROM admin_users     │
│ WHERE username & password   │
└────────────┬────────────────┘
             │
        ┌────┴────┐
        │          │
     ✓ Found    ✗ Not Found
        │          │
        ▼          ▼
    ┌─────┐    ┌──────────┐
    │ Set │    │  Error   │
    │Sess │    │ Message  │
    └──┬──┘    │ Show Login
       │       └─────┬─────
       ▼             │
    ┌────────┐      │
    │Redirect│      │
    │/admin  │      │
    └────────┘      │
       ✓           │
                    ▼
                ┌─────────────┐
                │ Stay on     │
                │ /login.php  │
                └─────────────┘
```

---

## 🔐 File Struktur Login

### File Locations:
```
📁 Login Page:        /embunvisual/public/login.php
📁 Admin Entry:       /embunvisual/admin.php
📁 Actual Admin:      /embunvisual/admin/admin.php
📁 Config:            /embunvisual/config/config.php
📁 Bootstrap:         /embunvisual/config/bootstrap.php
```

### Database Table:
```sql
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255),  -- MD5 hash
    email VARCHAR(100),
    role ENUM('Super Admin', 'Staff', 'Basic'),
    nama_lengkap VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 📝 Database Admin Credentials

Cek atau tambah admin baru:

### View Admin Users:
```sql
SELECT username, role, created_at FROM admin_users;
```

### Add New Admin:
```sql
INSERT INTO admin_users
(username, password, email, role, nama_lengkap)
VALUES (
    'newadmin',
    MD5('mypassword'),
    'admin@embunvisual.com',
    'Super Admin',
    'Nama Admin Baru'
);
```

### Change Password:
```sql
UPDATE admin_users
SET password = MD5('newpassword')
WHERE username = 'embeditor';
```

### Reset to Default:
```sql
UPDATE admin_users
SET password = MD5('embeditor123')
WHERE username = 'embeditor';
```

---

## 🎯 Quick Login Reference

| Aksi | URL |
|------|-----|
| **Login** | http://localhost/embunvisual/login.php |
| **Admin** | http://localhost/embunvisual/admin.php |
| **Logout** | Click "Logout" di admin menu |
| **Forgot Password** | (jika ada) di login page |

---

## ⚙️ Session Configuration

### Settings di Bootstrap:
```php
// Session timeout: 24 jam
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
```

### Ubah Session Timeout:
```php
// 1 jam (3600 detik)
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

// 7 hari (604800 detik)
ini_set('session.gc_maxlifetime', 604800);
session_set_cookie_params(604800);
```

---

## 🔍 Test Login

### Manual Test dengan PHP:

```php
<?php
require_once 'config/bootstrap.php';

// Test database connection
if ($conn) {
    echo "✓ Database connected";
} else {
    echo "✗ Database connection failed";
}

// Query admin users
$result = mysqli_query($conn, "SELECT username, role FROM admin_users");
while ($row = mysqli_fetch_assoc($result)) {
    echo "User: " . $row['username'] . " - Role: " . $row['role'] . "<br>";
}
?>
```

### Manual Test with cURL:

```bash
curl -X POST http://localhost/embunvisual/login.php \
  -d "login=1&username=embeditor&password=embeditor123" \
  -c cookies.txt

# Then access admin
curl http://localhost/embunvisual/admin.php \
  -b cookies.txt
```

---

## 📋 Troubleshooting Checklist

Jika login tidak berhasil, check:

- [ ] MySQL service running
- [ ] Database `embun_visual` exists
- [ ] Table `admin_users` exists
- [ ] Data admin dalam database (SELECT * FROM admin_users)
- [ ] Username & password benar (case sensitive)
- [ ] Bootstrap.php loaded dengan benar
- [ ] config/constants.php credentials correct
- [ ] Session folder writable (php tmp folder)
- [ ] Browser cookies enabled
- [ ] No browser cache issues (Ctrl+F5)

---

## 🆘 Need Help?

### Check Files:
1. **Login Page:** `/public/login.php`
2. **Admin Page:** `/admin/admin.php`
3. **Bootstrap:** `/config/bootstrap.php`
4. **Database Config:** `/config/constants.php`

### Database Query to Debug:
```sql
-- Check if admin exists
SELECT id, username, password, role FROM admin_users
WHERE username = 'embeditor';

-- Check password hash
SELECT MD5('embeditor123');
-- Result: dcc33ba44d7e6d0aba9d7c52e8aedb85
```

### PHP Debug:
```php
<?php
require_once 'config/bootstrap.php';

// Test login process
$user = 'embeditor';
$pass = md5('embeditor123');

$query = "SELECT * FROM admin_users WHERE username='$user' AND password='$pass'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "✓ Login credentials valid";
} else {
    echo "✗ Login failed - Check credentials";
}
?>
```

---

**Last Updated:** March 9, 2026
**Version:** 1.0.0
**Status:** 🟢 Production Ready
