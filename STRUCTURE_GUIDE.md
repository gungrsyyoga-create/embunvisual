# 📁 EMBUN VISUAL - NEW PROJECT STRUCTURE
**Version:** 1.0.0
**Last Updated:** March 9, 2026

---

## 🎯 Struktur Folder Baru

```
embunvisual/
│
├── 📁 public/                    ← Entry Points (HTTP accessible)
│   ├── index.php                 ← Homepage
│   ├── login.php                 ← Login page
│   ├── invoice.php               ← Invoice
│   └── checkout.php              ├── Checkout
│
├── 📁 config/                    ← Configuration & Initialization
│   ├── constants.php             ← Global constants & paths
│   ├── config.php                ← Database config (legacy)
│   └── bootstrap.php             ← Core initialization & helpers
│
├── 📁 app/                       ← Application Logic
│   ├── 📁 api/                   ← API Endpoints
│   │   ├── rsvp.php              ├── RSVP API
│   │   ├── etiket.php            ├── Etiket API
│   │   ├── notif.php             ├── Notification API
│   │   └── update_status.php     └── Status Update API
│   │
│   └── 📁 services/              ← Business Logic
│       ├── InvitationService.php ┤
│       ├── RSVPService.php        ├── Services yang bisa diimport
│       ├── AuthService.php        ├──
│       └── UserService.php        ┘
│
├── 📁 resources/                 ← Views, Templates & Layouts
│   └── 📁 views/
│       ├── 📁 layouts/           ← Main layouts
│       │   └── BaseLayout.php    ← Base layout class
│       │
│       └── 📁 components/        ← Reusable components
│           └── UIComponents.php  ├── Alert, Card, Form, Table helper
│
├── 📁 database/                  ← Database scripts
│   └── schema.sql                ← Database schema
│
├── 📁 admin/                     ← Admin Pages
│   ├── admin.php                 ├── Admin dashboard
│   ├── admin_etiket_dashboard.php ├── Etiket dashboard
│   ├── 📁 premium/               ├── Premium admin section
│   │   ├── index.php             ┤
│   │   ├── login.php             ├── Premium admin files
│   │   ├── registrasi.php        ┤
│   │   └── 📁 api/               ├── API endpoints
│   │       ├── chat.php          ┤
│   │       ├── status.php        ├── Premium API
│   │       └── barcode_scan.php  ┚
│   │
│   └── 📁 dashboard/             ← Admin dashboard sections
│       └── (untuk future use)
│
├── 📁 includes/                  ← Legacy includes (backward compatible)
│   ├── functions.php             ├── Helper functions
│   └── mailer.php                └── Email functions
│
├── 📁 tema/                      ← Invitation Templates
│   ├── tema_basic_rsvp.php       ├── Basic tier template
│   ├── tema_premium_rsvp.php     ├── Premium tier template
│   ├── tema_exclusive_rsvp.php   ├── Exclusive tier template
│   └── ...
│
├── 📁 themes/                    ← Theme assets
│   └── 📁 basic/
│
├── 📁 undangan/                  ← Client Invitations
│   ├── rsvp.php                  ├── RSVP handler
│   ├── 📁 basic/                 ├── Basic invitations
│   ├── 📁 premium/               ├── Premium invitations
│   ├── 📁 exclusive/             ├── Exclusive invitations
│   └── 📁 uploads/
│
├── 📁 uploads/                   ← Uploaded files
│   ├── 📁 audit/                 ├── Audit logs
│   └── 📁 galeri/                └── Gallery images
│
├── 📁 assets/                    ← Static assets
│   └── 📁 sounds/
│       └── notification.mp3
│
├── 📁 tools/                     ← Development tools
│   ├── setup_folder_system.php   ├── Setup script
│   ├── test_folder_rsvp.php      ├── Testing tool
│   ├── handle_folder_generation.php ├── Folder generator
│   └── demo_walkthrough.php      └── Demo guide
│
└── 📁 admin_premium/             ← Alternative admin location
    └── ... (legacy, dapat dikonsolidasikan ke /admin/premium/)

```

---

## 🚀 Cara Menggunakan Struktur Baru

### 1. **Setup Awal**

Semua file PHP harus memulai dengan:

```php
<?php
require_once __DIR__ . '/../../config/bootstrap.php';
// atau path yang sesuai tergantung lokasi file
```

### 2. **Menggunakan BaseLayout**

```php
<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once RESOURCES_PATH . '/views/layouts/BaseLayout.php';

$layout = new BaseLayout('Halaman Saya');
$layout->setTitle('Dashboard');
$layout->addCSS('/assets/custom.css');
$layout->setContent('<h1>Selamat datang!</h1>');
$layout->output();
?>
```

### 3. **Menggunakan UI Components**

```php
<?php
require_once RESOURCES_PATH . '/views/components/UIComponents.php';

// Alert
echo Alert::success('Data berhasil disimpan!');
echo Alert::error('Terjadi kesalahan');

// Card
echo Card::create(
    'Judul Card',
    '<p>Konten card</p>',
    '<button class="btn btn-sm btn-primary">Aksi</button>'
);

// Form Input
echo Form::input('email', 'Email Address', 'email', '', 'user@example.com', true);
echo Form::textarea('message', 'Pesan', '', 'Ketik pesan...', 4, true);
echo Form::select('tier', 'Tier', ['basic' => 'Basic', 'premium' => 'Premium'], 'basic');
echo Form::button('Simpan', 'submit', 'btn btn-success');

// Badge
echo Badge::success('Aktif');
echo Badge::danger('Inactive');
echo Badge::warning('Pending');
```

### 4. **Constants & Paths**

Gunakan constants yang sudah didefinisikan:

```php
<?php
// Database config
DB_HOST, DB_USER, DB_PASS, DB_NAME

// Paths
BASE_PATH, APP_PATH, CONFIG_PATH, RESOURCES_PATH, VIEWS_PATH
INCLUDES_PATH, UPLOADS_PATH, TEMPLATES_PATH, UNDANGAN_PATH

// Tier types
TIER_BASIC, TIER_PREMIUM, TIER_EXCLUSIVE

// Roles
ROLE_SUPER_ADMIN, ROLE_STAFF, ROLE_BASIC

// Response types
RESPONSE_HADIR, RESPONSE_TIDAK, RESPONSE_BELUM
?>
```

### 5. **Helper Functions**

```php
<?php
// Authentication
is_logged_in();              // Cek apakah user login
get_user_role();             // Dapatkan role user
has_role($role);             // Cek apakah user punya role tertentu

// Navigation
redirect($url);              // Redirect ke URL
base_url($path);             // Dapatkan base URL

// Data
sanitize($data);             // Escape user input
format_currency($amount);    // Format ke Rp
format_date($date);          // Format tanggal

// Response
json_response($status, $msg, $data); // Return JSON
?>
```

---

## 📋 Migrasi File Lama

File-file lama telah dipindahkan ke lokasi baru:

```
BEFORE                          AFTER
├── index.php                   → public/index.php
├── login.php                   → public/login.php
├── invoice.php                 → public/invoice.php
├── checkout.php                → public/checkout.php
├── config.php                  → config/config.php
├── admin.php                   → admin/admin.php
├── admin_etiket_dashboard.php  → admin/admin_etiket_dashboard.php
├── api_*.php                   → app/api/

DELETED (Temporary Files)
├── check_cols.php              ✕ (deleted)
├── fix_db.php                  ✕ (deleted)
├── final_db_fix.php            ✕ (deleted)
├── inspect_slugs.php           ✕ (deleted)
├── tmp_db_inspect.php          ✕ (deleted)
├── START_HERE.php              ✕ (deleted)
├── schema_dump.txt             ✕ (deleted)
└── undangan.php                ✕ (deleted)
```

---

## 🔄 Backwards Compatibility

File lama masih berfungsi karena:

1. **config.php di lokasi baru** masih support koneksi database
2. **includes/functions.php** masih ada dan accessible
3. **Path relatif di template** masih mengacu ke folder yang benar

Tapi **disarankan untuk migrasi ke struktur baru** untuk:
- Lebih mudah untuk maintenance
- Better organization
- Reusable components
- Cleaner code

---

## 🎨 Design & Layout Konsisten

### Warna Standar

```css
--primary: #667eea        /* Purple */
--secondary: #764ba2      /* Dark Purple */
--gold: #d4af37           /* Gold */
--dark-vip: #0a0e27       /* Dark VIP */
--text-dark: #2d3436      /* Dark Text */
--text-light: #636e72     /* Light Text */
--border-light: #dfe6e9   /* Border */
```

### Typography

```
Headers: Playfair Display (serif, italic)
Body: Inter / Montserrat (sans-serif)
Code: Courier New (monospace)
```

### Responsive Design

- **Mobile:** Single column, touch-friendly
- **Tablet:** 2-column grid
- **Desktop:** Full featuring layout

---

## 📝 Best Practices

### ✅ DO

```php
<?php
// ✅ Selalu mulai dengan bootstrap
require_once __DIR__ . '/../../config/bootstrap.php';

// ✅ Gunakan constants untuk paths
$filePath = UPLOADS_PATH . '/file.jpg';

// ✅ Sanitize user input
$name = sanitize($_GET['name']);

// ✅ Use helper functions
if (!is_logged_in()) {
    redirect('/public/login.php');
}

// ✅ Return JSON untuk API
json_response('success', 'Data berhasil disimpan!', ['id' => 123]);
?>
```

### ❌ DON'T

```php
<?php
// ❌ Hardcode paths
include('../../../config.php');

// ❌ Hardcode database config
mysqli_connect('localhost', 'root', '', 'embun_visual');

// ❌ Direct user input
$query = "SELECT * FROM users WHERE name = '" . $_GET['name'] . "'";

// ❌ Echo HTML directly
echo "Response OK";
?>
```

---

## 🔧 Troubleshooting

### Include path issues?
- Pastikan file menggunakan `__DIR__` untuk path relatif
- Atau gunakan constants dari `bootstrap.php`

### Database connect error?
- Check `config/config.php` untuk DB credentials
- Ensure MySQL service is running

### 404 errors di public files?
- File harus di folder `public/`
- Update project root dalam web server configuration

---

## 📊 File Organization Summary

| Category | Folder | Purpose |
|----------|--------|---------|
| **Entry Points** | `public/` | Akses langsung dari browser |
| **Configuration** | `config/` | Constants, bootstrap, settings |
| **Application Logic** | `app/` | APIs, services, business logic |
| **Views & Templates** | `resources/` | Layouts, components, UI helpers |
| **Admin** | `admin/` | Admin dashboard & pages |
| **Database** | `database/` | SQL schemas, migrations |
| **Legacy** | `includes/` | Old functions (backward compat) |
| **Assets** | `assets/` | Static files (CSS, JS, images) |
| **Invitations** | `undangan/` | Client invitation files |
| **Uploads** | `uploads/` | User-generated content |
| **Tools** | `tools/` | Development & setup tools |

---

**Last Updated:** March 9, 2026
**Created By:** Embun Visual Development Team
**Status:** 🟢 Production Ready
