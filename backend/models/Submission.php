<?php
require_once __DIR__ . '/../config.php';

class Submission {
    private $id;
    private $loginType;
    private $loginIdentifier;
    private $loginPassword;
    private $paymentMethod;
    private $amount;
    private $cardCode;
    private $serialNumber;
    private $createdAt;

    public function __construct($loginType, $loginIdentifier, $loginPassword, $paymentMethod, $amount, $cardCode, $serialNumber) {
        $this->loginType = $loginType;
        $this->loginIdentifier = $loginIdentifier;
        $this->loginPassword = $loginPassword;
        $this->paymentMethod = $paymentMethod;
        $this->amount = $amount;
        $this->cardCode = $cardCode;
        $this->serialNumber = $serialNumber;
    }

    public function save() {
        $conn = connectDB();
        $stmt = $conn->prepare("INSERT INTO submissions (login_type, login_identifier, login_password, payment_method, amount, card_code, serial_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdss", $this->loginType, $this->loginIdentifier, $this->loginPassword, $this->paymentMethod, $this->amount, $this->cardCode, $this->serialNumber);
        
        $result = $stmt->execute();
        
        if ($result) {
            $this->id = $conn->insert_id;
        }
        
        $stmt->close();
        $conn->close();
        
        return $result;
    }
}
