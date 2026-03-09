# 🛠️ DEVELOPER QUICK REFERENCE - EMBUN VISUAL
**Panduan Cepat untuk Pengembang**

---

## ⚡ Quick Start

### Create New Page
```php
<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once RESOURCES_PATH . '/views/layouts/BaseLayout.php';
require_once RESOURCES_PATH . '/views/components/UIComponents.php';

// Permission check
if (!is_logged_in()) redirect('/public/login.php');
if (!has_role(ROLE_SUPER_ADMIN)) json_response('error', 'Forbidden');

// Create layout
$layout = new BaseLayout('My Page');
$layout->setTitle('Page Title');
$layout->showSidebar(true);
$layout->showFooter(true);

// Build content
$content = Alert::success('Welcome back!');
$content .= Card::create('Dashboard', '<p>Content here</p>');

$layout->setContent($content);
$layout->output();
?>
```

### Create New API
```php
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response('error', 'POST only');
}

$action = sanitize($_POST['action'] ?? '');

switch ($action) {
    case 'create':
        $name = sanitize($_POST['name']);
        $query = "INSERT INTO table (name) VALUES ('{$name}')";
        if (mysqli_query($conn, $query)) {
            json_response('success', 'Created', ['id' => mysqli_insert_id($conn)]);
        } else {
            json_response('error', 'Database error');
        }
        break;
    default:
        json_response('error', 'Unknown action');
}
?>
```

---

## 🎨 Common UI Patterns

### Alert
```php
<?php
echo Alert::success('✓ Data saved successfully!');
echo Alert::error('✗ Something went wrong');
echo Alert::warning('⚠ Please check your input');
echo Alert::info('ℹ Note: This is important');
?>
```

### Form
```php
<?php
echo '<form method="POST" action="/app/api/save.php">';
echo Form::input('name', 'Full Name', 'text', '', 'John Doe', true);
echo Form::input('email', 'Email Address', 'email', '', '', true);
echo Form::textarea('message', 'Message', '', 'Type your message...', 5, true);
echo Form::select('tier', 'Tier', [
    TIER_BASIC => 'Basic',
    TIER_PREMIUM => 'Premium',
    TIER_EXCLUSIVE => 'Exclusive'
], TIER_BASIC, true);
echo Form::button('Submit', 'submit', 'btn btn-primary');
echo '</form>';
?>
```

### Card Layout
```php
<?php
$content = <<<HTML
<h5>Title</h5>
<p>Description text</p>
<button class="btn btn-sm btn-primary">Action</button>
HTML;

$footer = <<<HTML
<small class="text-muted">Created 2 days ago</small>
HTML;

echo Card::create('Card Title', $content, $footer, 'mb-3');
?>
```

### Table
```php
<?php
$headers = ['ID', 'Name', 'Email', 'Status'];
$rows = [
    ['1', 'John Doe', 'john@mail.com', Badge::success('Active')],
    ['2', 'Jane Smith', 'jane@mail.com', Badge::warning('Pending')],
    ['3', 'Bob Johnson', 'bob@mail.com', Badge::danger('Inactive')]
];

echo Table::create($headers, $rows, 'table-striped');
?>
```

---

## 🔐 Authorization Patterns

### Check If Logged In
```php
<?php
if (!is_logged_in()) {
    redirect('/public/login.php');
}
// Continue with logged-in code
?>
```

### Check Role
```php
<?php
$role = get_user_role();

if ($role === ROLE_SUPER_ADMIN) {
    // Admin only code
}

if (has_role(ROLE_STAFF)) {
    // Staff code
}
?>
```

### API Authorization
```php
<?php
header('Content-Type: application/json');
require_once 'config/bootstrap.php';

// Check if user is logged in
if (!is_logged_in()) {
    json_response('error', 'Unauthorized', ['code' => 401]);
}

// Check role
if (!has_role(ROLE_SUPER_ADMIN)) {
    json_response('error', 'Forbidden', ['code' => 403]);
}
?>
```

---

## 📝 Database Patterns

### Query with Sanitization
```php
<?php
$name = sanitize($_POST['name']);
$email = sanitize($_POST['email']);

$query = "INSERT INTO users (name, email) VALUES ('{$name}', '{$email}')";
$result = mysqli_query($conn, $query);

if ($result) {
    $id = mysqli_insert_id($conn);
} else {
    $error = mysqli_error($conn);
}
?>
```

### Fetch Single Row
```php
<?php
$id = sanitize($_GET['id']);
$query = "SELECT * FROM users WHERE id = {$id}";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Name: " . $row['name'];
} else {
    echo "Not found";
}
?>
```

### Fetch All Rows
```php
<?php
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo count($users) . " users found";
?>
```

### Update Data
```php
<?php
$id = sanitize($_POST['id']);
$name = sanitize($_POST['name']);

$query = "UPDATE users SET name = '{$name}', updated_at = NOW() WHERE id = {$id}";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "Updated: " . mysqli_affected_rows($conn) . " rows";
}
?>
```

### Delete Data
```php
<?php
$id = sanitize($_POST['id']);
$query = "DELETE FROM users WHERE id = {$id}";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "Deleted: " . mysqli_affected_rows($conn) . " rows";
}
?>
```

---

## 📤 File Upload Handling

### Basic Upload
```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Validate
    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($ext), $allowed)) {
        json_response('error', 'File type not allowed');
    }

    // Upload
    $filename = uniqid() . '.' . $ext;
    $destination = UPLOADS_PATH . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        json_response('success', 'File uploaded', ['filename' => $filename]);
    } else {
        json_response('error', 'Upload failed');
    }
}
?>
```

---

## 💬 Session Handling

### Get Session Data
```php
<?php
$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['user_name'] ?? 'Guest';
$userRole = $_SESSION['role'] ?? 'Guest';

echo "Welcome, " . htmlspecialchars($userName);
?>
```

### Set Session Data
```php
<?php
$_SESSION['user_id'] = 123;
$_SESSION['user_name'] = 'John Doe';
$_SESSION['role'] = ROLE_SUPER_ADMIN;
$_SESSION['login_time'] = time();
?>
```

### Clear Session (Logout)
```php
<?php
session_destroy();
redirect('/public/login.php');
?>
```

---

## 🌍 URL Helpers

### Generate Full URL
```php
<?php
$url = base_url('/page.php');
// Output: http://localhost/embunvisual/page.php

$url = base_url('/public/index.php?id=123');
// Output: http://localhost/embunvisual/public/index.php?id=123
?>
```

### Redirect
```php
<?php
redirect('/public/home.php');
// HTTP 302 redirect

redirect('http://external.com');
// External redirect
?>
```

---

## 📱 Data Formatting

### Currency
```php
<?php
echo format_currency(1000000);      // Rp 1.000.000
echo format_currency(50000.50);     // Rp 50.000,50
?>
```

### Date
```php
<?php
echo format_date('2026-03-09');                      // 09/03/2026
echo format_date($row['created_at'], 'd-m-Y');     // 09-03-2026
echo format_date($row['updated_at'], 'Y-m-d H:i'); // 2026-03-09 14:30
?>
```

### Sanitize Input
```php
<?php
$name = sanitize($_GET['name']);
$data = sanitize($_POST);  // Sanitize entire array

// Removes special characters and escapes for MySQL
?>
```

---

## 🎯 Common Tasks Checklist

### Create New Page
- [ ] Create file in `public/`
- [ ] Load bootstrap & BaseLayout
- [ ] Check authentication
- [ ] Create layout instance
- [ ] Add content
- [ ] Call output()

### Create New API
- [ ] Create file in `app/api/`
- [ ] Set JSON header
- [ ] Load bootstrap
- [ ] Check auth & role
- [ ] Validate input
- [ ] Sanitize data
- [ ] Execute query
- [ ] Return json_response()

### Connect to Database
- [ ] Load bootstrap (auto-connects)
- [ ] Use `$conn` object
- [ ] Sanitize user input
- [ ] Execute query
- [ ] Check errors with `mysqli_error()`

### Create Form
- [ ] Use `Form::*()` methods
- [ ] Add CSRF token (optional)
- [ ] Point to API endpoint
- [ ] Use POST method
- [ ] Validate on server-side

### Display Table
- [ ] Query database
- [ ] Build array of rows
- [ ] Use `Table::create()`
- [ ] Pass headers & rows
- [ ] Add pagination (optional)

---

## 🚀 Performance Tips

### Query Optimization
```php
<?php
// ❌ Avoid N+1 queries
foreach ($users as $user) {
    $result = mysqli_query($conn, "SELECT * FROM posts WHERE user_id = {$user['id']}");
}

// ✅ Use JOIN instead
$query = "SELECT u.*, p.* FROM users u LEFT JOIN posts p ON u.id = p.user_id";
?>
```

### Caching
```php
<?php
// Cache expensive queries
$cache_key = 'users_count';
$cache = apcu_fetch($cache_key);

if ($cache === false) {
    $query = "SELECT COUNT(*) FROM users";
    $result = mysqli_query($conn, $query);
    $cache = mysqli_fetch_assoc($result)['COUNT(*)'];
    apcu_store($cache_key, $cache, 3600);  // 1 hour TTL
}
?>
```

### Asset Loading
```php
<?php
// Combine & minify CSS/JS
$layout->addCSS('/assets/built.min.css');
$layout->addJS('/assets/built.min.js');

// Use CDN for external libraries
$layout->addCSS('https://cdn.jsdelivr.net/npm/bootstrap@5/...');
?>
```

---

## 🔍 Debugging

### Error Checking
```php
<?php
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (mysqli_error($conn)) {
        echo "DB Error: " . mysqli_error($conn);
    }
}
?>
```

### Logging
```php
<?php
function log_action($action, $details = '') {
    global $conn;

    $action = sanitize($action);
    $details = sanitize($details);
    $user_id = $_SESSION['user_id'] ?? 0;

    $query = "INSERT INTO audit_logs (user_id, action, details)
              VALUES ({$user_id}, '{$action}', '{$details}')";
    mysqli_query($conn, $query);
}

log_action('User login', 'User ID: ' . $_SESSION['user_id']);
?>
```

### Debugging Output
```php
<?php
// Check variable value
echo '<pre>';
print_r($_POST);
echo '</pre>';

// JSON debug
json_response('debug', 'Variables', [
    'post' => $_POST,
    'session' => $_SESSION,
    'user_id' => $_SESSION['user_id'] ?? null
]);
?>
```

---

## 📚 File Locations Reference

| Resource | Location |
|----------|----------|
| Constants | `config/constants.php` |
| Bootstrap | `config/bootstrap.php` |
| Database Config | `config/config.php` |
| Layouts | `resources/views/layouts/` |
| Components | `resources/views/components/` |
| Functions | `includes/functions.php` |
| API Template | `app/api/TEMPLATE.php` |
| Public Files | `public/` |
| Admin Files | `admin/` |
| Templates | `tema/` |
| Uploads | `uploads/` |

---

## 🆘 Getting Help

### Read Docs
1. `STRUCTURE_GUIDE.md` - Complete structure
2. `MIGRATION_GUIDE.md` - How to update old code
3. `CLEANUP_SUMMARY.md` - What was cleaned

### Check Code
- Look at existing files for examples
- Use `app/api/TEMPLATE.php` as reference
- Check `resources/views/components/UIComponents.php` for UI patterns

### Debug Issues
- Enable `APP_DEBUG` in `config/constants.php`
- Check error logs
- Use `error_reporting(E_ALL)`
- Test with raw SQL queries

---

**Last Updated:** March 9, 2026
**Quick Reference Version:** 1.0.0
