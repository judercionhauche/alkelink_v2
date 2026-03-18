<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function get_locale(): string
{
    $allowed = ['en', 'pt'];
    $locale = $_SESSION['locale'] ?? 'en';
    $locale = strtolower($locale);
    if (!in_array($locale, $allowed, true)) {
        $locale = 'en';
    }
    return $locale;
}

function set_locale(string $lang): string
{
    $allowed = ['en', 'pt'];
    $lang = strtolower(trim($lang));
    if (!in_array($lang, $allowed, true)) {
        return get_locale();
    }
    $_SESSION['locale'] = $lang;
    return $lang;
}

function load_translations(): array
{
    static $translations;
    if ($translations !== null) {
        return $translations;
    }

    $locale = get_locale();
    $basePath = __DIR__ . '/lang/';
    $localeFile = $basePath . $locale . '.php';
    $fallbackFile = $basePath . 'en.php';

    $translations = [];
    if (file_exists($fallbackFile)) {
        $translations = include $fallbackFile;
    }

    if (file_exists($localeFile) && $localeFile !== $fallbackFile) {
        $localeTranslations = include $localeFile;
        if (is_array($localeTranslations)) {
            $translations = array_merge($translations, $localeTranslations);
        }
    }

    if (!is_array($translations)) {
        $translations = [];
    }

    return $translations;
}

function t(string $key, array $replacements = []): string
{
    $translations = load_translations();
    $text = $translations[$key] ?? $key;
    if ($replacements) {
        $text = strtr($text, $replacements);
    }
    return $text;
}

function __($key, array $replacements = []): string
{
    return t($key, $replacements);
}

function get_supported_locales(): array
{
    return [
        'en' => 'English',
        'pt' => 'Português',
    ];
}
