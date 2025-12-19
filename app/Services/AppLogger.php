<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Class AppLogger
 * 
 * A Singleton class responsible for centralized logging within the ATM system.
 * This satisfies the "Singleton Design Pattern" requirement.
 * 
 * Usage:
 * AppLogger::getInstance()->log('message');
 */
class AppLogger
{
    /**
     * @var AppLogger|null
     */
    private static ?AppLogger $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        // Initialization logic if needed
    }

    /**
     * Get the single instance of the class.
     *
     * @return AppLogger
     */
    public static function getInstance(): AppLogger
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Log a message to the application logs.
     *
     * @param string $message
     * @param string $level
     * @return void
     */
    public function log(string $message, string $level = 'info'): void
    {
        Log::log($level, "[ATM SYSTEM] " . $message);
    }
}
