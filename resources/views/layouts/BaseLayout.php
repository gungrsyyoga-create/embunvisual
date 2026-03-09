<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - BASE LAYOUT COMPONENT
// ═══════════════════════════════════════════════════════════════

/**
 * Base Layout Class
 * Menyediakan struktur HTML dasar dan komponenyang dapat digunakan kembali
 */
class BaseLayout {

    private $title = '';
    private $meta = [];
    private $css = [];
    private $js = [];
    private $content = '';
    private $showHeader = true;
    private $showSidebar = true;
    private $showFooter = true;

    public function __construct($title = '') {
        $this->title = $title ?: APP_NAME;
        $this->addDefaultAssets();
    }

    /**
     * Add default CSS and JS files
     */
    private function addDefaultAssets() {
        // Bootstrap CSS
        $this->addCSS('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');

        // Font Awesome
        $this->addCSS('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

        // Google Fonts
        $this->addCSS('https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap');

        // Bootstrap JS
        $this->addJS('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js');
    }

    /**
     * Set page title
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Add CSS file
     */
    public function addCSS($url) {
        $this->css[] = $url;
        return $this;
    }

    /**
     * Add JavaScript file
     */
    public function addJS($url) {
        $this->js[] = $url;
        return $this;
    }

    /**
     * Add meta tag
     */
    public function addMeta($name, $content) {
        $this->meta[$name] = $content;
        return $this;
    }

    /**
     * Add page content
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Show/hide header
     */
    public function showHeader($show = true) {
        $this->showHeader = $show;
        return $this;
    }

    /**
     * Show/hide sidebar
     */
    public function showSidebar($show = true) {
        $this->showSidebar = $show;
        return $this;
    }

    /**
     * Show/hide footer
     */
    public function showFooter($show = true) {
        $this->showFooter = $show;
        return $this;
    }

    /**
     * Render HTML head
     */
    private function renderHead() {
        $metaTags = '';
        foreach ($this->meta as $name => $content) {
            $metaTags .= "<meta name=\"{$name}\" content=\"{$content}\">\n";
        }

        $cssLinks = '';
        foreach ($this->css as $css) {
            $cssLinks .= "<link rel=\"stylesheet\" href=\"{$css}\">\n";
        }

        $jsLinks = '';
        foreach ($this->js as $js) {
            $jsLinks .= "<script src=\"{$js}\"></script>\n";
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->title}</title>
    {$metaTags}
    {$cssLinks}
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --gold: #d4af37;
            --dark-vip: #0a0e27;
            --text-dark: #2d3436;
            --text-light: #636e72;
            --border-light: #dfe6e9;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: #f8f9fa;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .layout-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .layout-main {
            flex: 1;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .layout-main {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
HTML;
    }

    /**
     * Render Navigation
     */
    private function renderHeader() {
        if (!$this->showHeader) return '';

        $username = $_SESSION['user_name'] ?? $_SESSION['admin_name'] ?? 'User';
        $role = get_user_role() ?? 'Guest';

        return <<<HTML
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <strong>{APP_NAME}</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {$username} ({$role})
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="/public/profile.php"><i class="fas fa-cog"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
HTML;
    }

    /**
     * Render Sidebar
     */
    private function renderSidebar() {
        if (!$this->showSidebar) return '';

        return <<<HTML
<aside class="col-md-3 col-lg-2 d-md-block bg-white border-right" style="height: calc(100vh - 60px); overflow-y: auto;">
    <div class="list-group list-group-flush mt-3">
        <a href="/" class="list-group-item list-group-item-action">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="/admin.php" class="list-group-item list-group-item-action">
            <i class="fas fa-cogs"></i> Admin
        </a>
        <a href="/admin/admin_etiket_dashboard.php" class="list-group-item list-group-item-action">
            <i class="fas fa-ticket-alt"></i> Etiket
        </a>
        <a href="/undangan" class="list-group-item list-group-item-action">
            <i class="fas fa-envelope"></i> Undangan
        </a>
    </div>
</aside>
HTML;
    }

    /**
     * Render Footer
     */
    private function renderFooter() {
        if (!$this->showFooter) return '';

        $year = date('Y');
        return <<<HTML
<footer class="bg-white border-top mt-auto py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted mb-0">&copy; {$year} {APP_NAME} v{APP_VERSION}. Semua hak dilindungi.</p>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <a href="#" class="text-decoration-none text-muted">Bantuan</a> |
                    <a href="#" class="text-decoration-none text-muted">Kebijakan</a> |
                    <a href="#" class="text-decoration-none text-muted">Kontak</a>
                </small>
            </div>
        </div>
    </div>
</footer>
HTML;
    }

    /**
     * Render complete HTML
     */
    public function render() {
        $head = $this->renderHead();
        $header = $this->renderHeader();
        $sidebar = $this->renderSidebar();
        $footer = $this->renderFooter();

        $mainContent = <<<HTML
{$header}
<div class="layout-wrapper">
    {$sidebar}
    <div class="layout-content col">
        <main class="layout-main">
            {$this->content}
        </main>
    </div>
</div>
{$footer}

HTML;

        $jsContent = '';
        foreach ($this->js as $js) {
            // Skip if already in head
        }

        return $head . $mainContent . '</body></html>';
    }

    /**
     * Output layout to browser
     */
    public function output() {
        echo $this->render();
    }
}

?>
