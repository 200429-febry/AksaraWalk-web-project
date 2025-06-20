<?php
// public/helpers/NotificationSender.php (Contoh)
class NotificationSender {
    public static function sendNewOrderNotification($orderId, $totalAmount) {
        $notification_data = [
            'order_id' => $orderId,
            'total_amount' => $totalAmount,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $ch = curl_init('http://localhost:3000/notify-new-order'); // Ganti port sesuai Node.js
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification_data));
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Log response jika perlu
        error_log("Notification sent. HTTP Code: " . $http_code . " Response: " . $response);
    }
}
?>