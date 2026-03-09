# 🔄 MIGRATION GUIDE - EMBUN VISUAL
**Dari Struktur Lama ke Struktur Baru**

---

## 📋 Ringkasan Perubahan

### File yang Dipindahkan

| Tujuan | File Lama | File Baru | Status |
|--------|-----------|----------|--------|
| Entry Points | `index.php` | `public/index.php` | ✅ Moved |
| | `login.php` | `public/login.php` | ✅ Moved |
| | `invoice.php` | `public/invoice.php` | ✅ Moved |
| | `checkout.php` | `public/checkout.php` | ✅ Moved |
| Configuration | `config.php` | `config/config.php` | ✅ Moved |
| Admin | `admin.php` | `admin/admin.php` | ✅ Moved |
| | `admin_etiket_dashboard.php` | `admin/admin_etiket_dashboard.php` | ✅ Moved |
| API | `api_rsvp.php` | `app/api/api_rsvp.php` | ✅ Moved |
| | `api_etiket.php` | `app/api/api_etiket.php` | ✅ Moved |
| | `api_notif.php` | `app/api/api_notif.php` | ✅ Moved |
| | `api_update_status.php` | `app/api/api_update_status.php` | ✅ Moved |

### File yang Dihapus (Temporary)

```
❌ check_cols.php           (debug file)
❌ fix_db.php               (debug file)
❌ final_db_fix.php         (debug file)
❌ inspect_slugs.php        (debug file)
❌ tmp_db_inspect.php       (debug file)
❌ START_HERE.php           (placeholder)
❌ schema_dump.txt          (schema reference)
❌ undangan.php             (old invitation system)
```

### File Baru (Structure)

```
✅ config/constants.php              (Global constants)
✅ config/bootstrap.php              (Core initialization)
✅ resources/views/layouts/BaseLayout.php  (Base layout class)
✅ resources/views/components/UIComponents.php  (UI helpers)
✅ app/api/TEMPLATE.php              (API template)
✅ STRUCTURE_GUIDE.md                (Structure documentation)
✅ MIGRATION_GUIDE.md                (This file)
```

---

## 🔗 Update URL References

### Lama → Baru

```
// Lama:
require 'config.php'

// Baru:
require_once __DIR__ . '/../../config/bootstrap.php'
```

### Include paths dalam templates

```
// Lama:
<?php include 'admin.php'; ?>

// Baru:
<?php require_once APP_PATH . '/../admin/admin.php'; ?>
```

---

## 🚀 Setup & Testing

### Step 1: Verifikasi Struktur
```bash
ls -la /xampp/htdocs/embunvisual/
# Pastikan folder: public/, admin/, app/, config/, resources/
```

### Step 2: Test Database Connection
```php
<?php
require_once 'config/bootstrap.php';
if ($conn) {
    echo "✓ Database connected";
} else {
    echo "✗ Database connection failed";
}
?>
```

### Step 3: Test BaseLayout
```php
<?php
require_once 'config/bootstrap.php';
require_once RESOURCES_PATH . '/views/layouts/BaseLayout.php';

$layout = new BaseLayout('Test Page');
$layout->setContent('<h1>Struktur baru berhasil!</h1>');
$layout->output();
?>
```

### Step 4: Test API Template
```bash
# Test API endpoint
curl -X POST http://localhost/embunvisual/app/api/TEMPLATE.php \
  -d "action=list"
```

---

## ⚠️ Backward Compatibility

### ✅ Masih kompatibel:

```php
<?php
// Old way masih bisa
include 'config.php';

// Because config.php now loads bootstrap
// Yang sebelumnya: session_start(), mysqli_connect()
// Sekarang: semua di bootstrap.php
?>
```

### ⚠️ Perlu diupdate:

```php
// OLD - Path hardcoded
include '../config.php';
include '../../includes/functions.php';

// NEW - Gunakan constants
require_once CONFIG_PATH . '/bootstrap.php';
require_once INCLUDES_PATH . '/functions.php';
```

---

## 📦 Folder Dependencies

### Bootstrap
- Diload pertama kali
- Initialize session & database
- Load constants & helpers

### Includes (Legacy)
- Tetap backward compatible
- Auto-loaded di bootstrap
- Gunakan untuk old code

### Resources
- Layouts & components baru
- UI helpers (Alert, Card, Form, etc)
- Reusable across pages

### App/API
- New API endpoints
- Follow standardized structure
- JSON response format

---

## 🔧 How to Update Old Code

### Contoh 1: Update Old Page

**Before:**
```php
<?php
session_start();
include '../../config.php';
include '../../includes/functions.php';

$user_name = $_SESSION['user_name'];
echo "Selamat datang " . htmlspecialchars($user_name);
?>
```

**After:**
```php
<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once RESOURCES_PATH . '/views/layouts/BaseLayout.php';

$layout = new BaseLayout('Halaman Saya');
$layout->setContent('<h1>Selamat datang ' . htmlspecialchars($_SESSION['user_name']) . '</h1>');
$layout->output();
?>
```

### Contoh 2: Update Old API

**Before:**
```php
<?php
session_start();
include '../../config.php';

$action = $_GET['action'];
if ($action == 'create') {
    // process...
    echo json_encode(['status' => 'ok']);
}
?>
```

**After:**
```php
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/bootstrap.php';

if (!is_logged_in()) {
    json_response('error', 'Unauthorized');
}

$action = $_POST['action'] ?? null;
if ($action == 'create') {
    // process...
    json_response('success', 'Created', ['data' => $data]);
}
?>
```

### Contoh 3: Update Template

**Before:**
```html
<!DOCTYPE html>
<html>
<head>
    <title>Page</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header><!-- header --></header>
    <!-- content -->
    <footer><!-- footer --></footer>
</body>
</html>
```

**After:**
```php
<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once RESOURCES_PATH . '/views/layouts/BaseLayout.php';

$layout = new BaseLayout('Page Title');
$layout->addCSS('/assets/style.css');
$layout->setContent(<<<HTML
<!-- Konten utama -->
<div class="container">
    <!-- content -->
</div>
HTML);
$layout->output();
?>
```

---

## 📚 Resource Files

### Constants Reference
**Location:** `config/constants.php`

```php
// Paths
BASE_PATH, APP_PATH, CONFIG_PATH, DATABASE_PATH, RESOURCES_PATH
VIEWS_PATH, INCLUDES_PATH, UPLOADS_PATH, TEMPLATES_PATH

// Database
DB_HOST, DB_USER, DB_PASS, DB_NAME

// Tiers & Roles
TIER_BASIC, TIER_PREMIUM, TIER_EXCLUSIVE
ROLE_SUPER_ADMIN, ROLE_STAFF, ROLE_BASIC

// Responses
RESPONSE_HADIR, RESPONSE_TIDAK, RESPONSE_BELUM
```

### Helper Functions
**Location:** `config/bootstrap.php`

```php
// Auth
is_logged_in()
get_user_role()
has_role($role)

// Navigation
redirect($url)
base_url($path)

// Data
sanitize($data)
format_currency($amount)
format_date($date)

// Response
json_response($status, $message, $data)
```

### UI Components
**Location:** `resources/views/components/UIComponents.php`

```php
// Alert
Alert::success($message)
Alert::error($message)
Alert::warning($message)
Alert::info($message)

// Card
Card::create($title, $content, $footer, $classes)

// Form
Form::input($name, $label, $type, $value, $placeholder, $required)
Form::textarea($name, $label, $value, $placeholder, $rows, $required)
Form::select($name, $label, $options, $selected, $required)
Form::button($text, $type, $classes)

// Table
Table::create($headers, $rows, $classes)

// Badge
Badge::create($text, $type)
Badge::success($text), Badge::danger($text), etc
```

---

## ✅ Validation Checklist

### Before Going Live

- [ ] All entry points work (`public/index.php`, etc)
- [ ] Database connection established
- [ ] Admin pages accessible (`admin/admin.php`)
- [ ] API endpoints responding correctly
- [ ] Asset paths updated (CSS, JS, images)
- [ ] BaseLayout renders correctly
- [ ] UI components display properly
- [ ] Old code still works (backward compat)
- [ ] No broken includes or requires
- [ ] Session initialization working

### Testing URLs

```
Homepage:      http://localhost/embunvisual/public/index.php
Login:         http://localhost/embunvisual/public/login.php
Admin:         http://localhost/embunvisual/admin/admin.php
API Test:      http://localhost/embunvisual/app/api/TEMPLATE.php
```

---

## 📞 Troubleshooting

### Error: "Unable to locate config/bootstrap.php"

**Solution:** Check your `__DIR__` relative path

```php
// If in public/index.php:
require_once __DIR__ . '/../config/bootstrap.php';

// If in admin/admin.php:
require_once __DIR__ . '/../config/bootstrap.php';

// If in app/api/something.php:
require_once __DIR__ . '/../../config/bootstrap.php';
```

### Error: "Call to undefined function is_logged_in()"

**Solution:** Make sure bootstrap.php is loaded

```php
<?php
// ✅ Correct
require_once CONFIG_PATH . '/bootstrap.php';
if (is_logged_in()) { }

// ❌ Wrong
include 'config.php';  // Old way doesn't load bootstrap
if (is_logged_in()) { }  // undefined!
```

### Error: "Permission denied" when creating files

**Solution:** Check folder permissions

```bash
# Linux/Mac
chmod 755 public/
chmod 755 admin/
chmod 755 uploads/

# Windows (usually fine)
# Just ensure web server has write access
```

---

## 🎯 Next Steps

1. **Test all pages individually** - Ensure no broken links
2. **Update remaining includes** - Replace relative paths with constants
3. **Refactor old API endpoints** - Use template in `app/api/TEMPLATE.php`
4. **Migrate views to BaseLayout** - Reduce code duplication
5. **Add more services** - Create `app/services/` for business logic
6. **Set up error logging** - Add logging to `app/services/Logger.php`

---

## 📞 Support

- Structure Guide: `STRUCTURE_GUIDE.md`
- Legacy Documentation: `FOLDER_STRUCTURE.txt`
- Implementation Docs: `DOKUMENTASI_ETIKET.md`

---

**Last Updated:** March 9, 2026
**Version:** 1.0.0
**Status:** Production Ready ✅
