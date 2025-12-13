<?php

declare(strict_types=1);

namespace App\Core;

class Translator
{
    private static ?self $instance = null;
    private array $translations = [];
    private string $lang;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->lang = $_SESSION['lang'] ?? 'en';
        $this->loadTranslations();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadTranslations(): void
    {
        $file = __DIR__ . '/../Lang/' . $this->lang . '/messages.php';
        if (file_exists($file)) {
            $this->translations = require $file;
        } else {
            // Fallback to English if file not found
            $fallback = __DIR__ . '/../Lang/en/messages.php';
            if (file_exists($fallback)) {
                $this->translations = require $fallback;
            }
        }
    }

    public function translate(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }

    public function setLanguage(string $lang): void
    {
        if (in_array($lang, ['en', 'it', 'fr', 'de', 'es'])) {
            $_SESSION['lang'] = $lang;
            $this->lang = $lang;
            $this->loadTranslations();
        }
    }
    
    public function getLanguage(): string
    {
        return $this->lang;
    }
}
