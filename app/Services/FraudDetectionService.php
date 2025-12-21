<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\FraudLog;
use App\Models\User;

class FraudDetectionService
{
    /**
     * The single instance of the class.
     */
    private static $instance = null;

    /**
     * Threshold for deviation percentage to flag fraud.
     * If spending deviates more than this percentage, it's flagged.
     * e.g. 50% deviation
     */
    private $deviationThreshold = 30;

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) {
            return 0;
        }
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function checkFraud($userId, $amount, $latitude = null, $longitude = null)
    {
        $transactions = Transaction::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereIn('transaction_type', ['withdrawal', 'transfer'])
            ->get();

        $averageSpending = 0;
        $deviationPercentage = 0;
        $distanceKm = 0;
        $usualLat = null;
        $usualLon = null;

        if ($transactions->isNotEmpty()) {
            $totalSpent = $transactions->sum('amount');
            $count = $transactions->count();
            $averageSpending = $count > 0 ? $totalSpent / $count : 0;
            if ($averageSpending > 0) {
                $deviation = ($amount - $averageSpending) / $averageSpending;
                $deviationPercentage = $deviation * 100;
            }
        }

        $user = User::find($userId);
        if ($user && $user->latitude && $user->longitude) {
            $usualLat = $user->latitude;
            $usualLon = $user->longitude;
            $distanceKm = $this->calculateDistance($usualLat, $usualLon, $latitude, $longitude);
        }

        $isFraud = $deviationPercentage > $this->deviationThreshold;

        return [
            'is_fraud' => $isFraud,
            'average_spending' => $averageSpending,
            'deviation_percentage' => $deviationPercentage,
            'usual_latitude' => $usualLat,
            'usual_longitude' => $usualLon,
            'distance_km' => $distanceKm,
        ];
    }

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {}

    /**
     * Public static method to get the single instance of the class.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new FraudDetectionService();
        }
        return self::$instance;
    }

    /**
     * Detect potential fraud based on spending deviation.
     * 
     * Formula: (Transaction Amount - Average Spending) / Average Spending
     * 
     * @param int $userId
     * @param float $amount
     * @param string $transactionId
     * @param float|null $latitude
     * @param float|null $longitude
     * @return bool Returns true if fraud is detected, false otherwise.
     */
    public function detectFraud($userId, $amount, $transactionId, $latitude = null, $longitude = null, $pinAttempts = null)
    {
        $result = $this->checkFraud($userId, $amount, $latitude, $longitude);
        if ($result['is_fraud']) {
            // Log to database
            FraudLog::create([
                'transaction_id' => $transactionId,
                'user_id' => $userId,
                'transaction_amount' => $amount,
                'user_avg_spending' => $result['average_spending'],
                'deviation_percentage' => $result['deviation_percentage'],
                'transaction_latitude' => $latitude,
                'transaction_longitude' => $longitude,
                'usual_latitude' => $result['usual_latitude'],
                'usual_longitude' => $result['usual_longitude'],
                'distance_km' => $result['distance_km'],
                'pin_attempts' => $pinAttempts,
            ]);

            return true;
        }

        return false;
    }
}
