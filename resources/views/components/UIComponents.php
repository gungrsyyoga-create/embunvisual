<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - UI COMPONENTS
// ═══════════════════════════════════════════════════════════════

/**
 * Alert Component
 */
class Alert {
    public static function success($message) {
        return <<<HTML
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {$message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
HTML;
    }

    public static function error($message) {
        return <<<HTML
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {$message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
HTML;
    }

    public static function warning($message) {
        return <<<HTML
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i> {$message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
HTML;
    }

    public static function info($message) {
        return <<<HTML
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="fas fa-info-circle"></i> {$message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
HTML;
    }
}

/**
 * Card Component
 */
class Card {
    public static function create($title, $content, $footer = '', $classes = '') {
        $footerContent = $footer ? "<div class=\"card-footer bg-light\">{$footer}</div>" : '';

        return <<<HTML
<div class="card {$classes}">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">{$title}</h5>
    </div>
    <div class="card-body">
        {$content}
    </div>
    {$footerContent}
</div>
HTML;
    }
}

/**
 * Table Component
 */
class Table {
    public static function create($headers, $rows, $classes = '') {
        $headerRow = '<tr>' . implode('', array_map(fn($h) => "<th>{$h}</th>", $headers)) . '</tr>';

        $bodyRows = '';
        foreach ($rows as $row) {
            $cells = implode('', array_map(fn($cell) => "<td>{$cell}</td>", $row));
            $bodyRows .= "<tr>{$cells}</tr>";
        }

        return <<<HTML
<div class="table-responsive">
    <table class="table table-striped table-hover {$classes}">
        <thead class="table-light">
            {$headerRow}
        </thead>
        <tbody>
            {$bodyRows}
        </tbody>
    </table>
</div>
HTML;
    }
}

/**
 * Form Component
 */
class Form {
    public static function input($name, $label, $type = 'text', $value = '', $placeholder = '', $required = false) {
        $req = $required ? 'required' : '';
        $reqAsterix = $required ? '<span class="text-danger">*</span>' : '';

        return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="form-label">{$label} {$reqAsterix}</label>
    <input type="{$type}" class="form-control" id="{$name}" name="{$name}" value="{$value}" placeholder="{$placeholder}" {$req}>
</div>
HTML;
    }

    public static function textarea($name, $label, $value = '', $placeholder = '', $rows = 4, $required = false) {
        $req = $required ? 'required' : '';
        $reqAsterix = $required ? '<span class="text-danger">*</span>' : '';

        return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="form-label">{$label} {$reqAsterix}</label>
    <textarea class="form-control" id="{$name}" name="{$name}" rows="{$rows}" placeholder="{$placeholder}" {$req}>{$value}</textarea>
</div>
HTML;
    }

    public static function select($name, $label, $options, $selected = '', $required = false) {
        $req = $required ? 'required' : '';
        $reqAsterix = $required ? '<span class="text-danger">*</span>' : '';

        $optionsHtml = '<option value="">-- Pilih --</option>';
        foreach ($options as $value => $text) {
            $sel = $selected === $value ? 'selected' : '';
            $optionsHtml .= "<option value=\"{$value}\" {$sel}>{$text}</option>";
        }

        return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="form-label">{$label} {$reqAsterix}</label>
    <select class="form-select" id="{$name}" name="{$name}" {$req}>
        {$optionsHtml}
    </select>
</div>
HTML;
    }

    public static function button($text, $type = 'submit', $classes = 'btn btn-primary') {
        return "<button type=\"{$type}\" class=\"{$classes}\">{$text}</button>";
    }
}

/**
 * Badge Component
 */
class Badge {
    public static function create($text, $type = 'primary') {
        return "<span class=\"badge bg-{$type}\">{$text}</span>";
    }

    public static function success($text) {
        return self::create($text, 'success');
    }

    public static function danger($text) {
        return self::create($text, 'danger');
    }

    public static function warning($text) {
        return self::create($text, 'warning');
    }

    public static function info($text) {
        return self::create($text, 'info');
    }
}

?>
