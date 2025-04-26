<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if ($data && is_array($data)) {
    $_SESSION['orderItems'] = $data;
    file_put_contents("log.txt", "Saved Session:\n" . print_r($_SESSION, true));
    echo "Order saved.";
} else {
    file_put_contents("log.txt", "Invalid Data: " . file_get_contents("php://input"));
    echo "Failed to save order.";
}
?>
