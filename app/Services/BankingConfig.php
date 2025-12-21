<?php

namespace App\Services;

class BankingConfig
{
    /**
     * The single instance of the class.
     */
    private static $instance = null;

    /**
     * Configuration properties.
     */
    private $withdrawalLimit;
    private $transferLimit;
    private $minWithdrawal;
    private $minTransfer;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        // Initialize default banking configuration
        // In a real app, this might load from a database or secure vault
        $this->withdrawalLimit = 5000;
        $this->minWithdrawal = 10;
        $this->transferLimit = 10000;
        $this->minTransfer = 1;
    }

    /**
     * Prevent cloning of the instance.
     */
    private function __clone() {}

    /**
     * Prevent unserializing of the instance.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Public static method to get the single instance of the class.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new BankingConfig();
        }
        return self::$instance;
    }

    // Getters
    public function getWithdrawalLimit()
    {
        return $this->withdrawalLimit;
    }

    public function getTransferLimit()
    {
        return $this->transferLimit;
    }

    public function getMinWithdrawal()
    {
        return $this->minWithdrawal;
    }

    public function getMinTransfer()
    {
        return $this->minTransfer;
    }
}
