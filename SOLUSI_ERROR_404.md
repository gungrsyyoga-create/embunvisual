# ⚡ SOLUSI ERROR 404 - ADMIN PAGE NOT FOUND
**Cara mengakses admin jika .htaccess belum aktif**

---

## ❌ Error yang Terjadi:

```
Not Found
The requested URL was not found on this server.
Apache/2.4.58
```

### Penyebab:
1. `.htaccess` belum enabled di Apache
2. `mod_rewrite` belum aktif
3. Path rewrite tidak bekerja

---

## ✅ SOLUSI 1: Akses Admin Langsung (CEPAT!)

**GUNAKAN URL INI untuk akses admin sekarang:**

```
http://localhost/embunvisual/admin/admin.php
```

atau

```
http://localhost/embunvisual/public/login.php
```

---

## 🔧 SOLUSI 2: Enable .htaccess di Apache (PERMANEN)

### Step 1: Edit httpd.conf

Buka file:
```
C:\xampp\apache\conf\httpd.conf
```

### Step 2: Cari baris berikut (~line 230):

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride None
</Directory>
```

### Step 3: Ubah ke:

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
</Directory>
```

### Step 4: Pastikan mod_rewrite aktif

Cari section:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Pastikan **TIDAK** ada karakter `#` di depannya (jika ada, hapus).

### Step 5: Restart Apache

1. Buka XAMPP Control Panel
2. Click "Stop" pada Apache
3. Tunggu beberapa detik
4. Click "Start" pada Apache

---

## ✨ Setelah Restart, Akses URLs Berikut:

### Homepage:
```
http://localhost/embunvisual/
```

### Login:
```
http://localhost/embunvisual/login.php
```

### Admin Dashboard:
```
http://localhost/embunvisual/admin.php
```

atau

```
http://localhost/embunvisual/admin/
```

---

## 📋 AKSES URL YANG TERSEDIA SEKARANG

Gunakan URL-URL ini **SEBELUM** .htaccess diaktifkan:

| Halaman | URL | Status |
|---------|-----|--------|
| **Homepage** | `http://localhost/embunvisual/index.php` | ✅ Works |
| **Login** | `http://localhost/embunvisual/public/login.php` | ✅ Works |
| **Admin** | `http://localhost/embunvisual/admin/admin.php` | ✅ Works (GUNAKAN INI!) |
| **Etiket** | `http://localhost/embunvisual/admin/admin_etiket_dashboard.php` | ✅ Works |
| **Invoice** | `http://localhost/embunvisual/public/invoice.php` | ✅ Works |
| **Checkout** | `http://localhost/embunvisual/public/checkout.php` | ✅ Works |

---

## 🎯 SEKARANG LAKUKAN INI:

### 1. Login Admin (Buka di Browser):
```
http://localhost/embunvisual/public/login.php
```

### 2. Isi Form Login:
```
Username: embeditor
Password: embeditor123
```

### 3. Click "MASUK"

### 4. Jika Berhasil:
```
Akan redirect ke:
http://localhost/embunvisual/admin/admin.php
```

---

## 🔄 Flow Akses Saat Ini

```
Homepage
   ↓
http://localhost/embunvisual/index.php
   ↓
(Click Login)
   ↓
http://localhost/embunvisual/public/login.php
   ↓
(Input: embeditor / embeditor123)
   ↓
(Click MASUK)
   ↓
http://localhost/embunvisual/admin/admin.php
(ADMIN DASHBOARD)
```

---

## 📝 Data Login Test

```
Username: embeditor
Password: embeditor123
Role: Super Admin
```

---

## ✅ Checklist Before Login

- [ ] XAMPP Apache running (check XAMPP Control Panel)
- [ ] MySQL running (check XAMPP Control Panel)
- [ ] Can access homepage: `http://localhost/embunvisual/index.php`
- [ ] Ready to test login

---

## 🚨 Jika Masih Ada Error:

### Error: "Database connection failed"
**Solusi:**
1. Pastikan MySQL running di XAMPP
2. Check `config/constants.php` - sesuaikan credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'embun_visual');
   ```

### Error: "Username atau Password salah"
**Solusi:**
1. Pastikan username & password benar: `embeditor` / `embeditor123`
2. Check database punya akun ini:
   ```sql
   SELECT * FROM admin_users WHERE username = 'embeditor';
   ```

### Error: "No such file or directory" di config
**Solusi:**
Sudah diperbaiki! File sudah di-update dengan path baru.

---

## 🎯 QUICK START

Sekarang akses admin dengan URL ini:

### Direct Access (SEKARANG):
```
http://localhost/embunvisual/admin/admin.php
```

### Atau Login Dulu:
```
1. Buka: http://localhost/embunvisual/public/login.php
2. Username: embeditor
3. Password: embeditor123
4. Click MASUK
5. Auto redirect ke admin
```

---

**Status:** 🟢 Ready untuk Testing
