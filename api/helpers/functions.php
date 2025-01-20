<?php
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function send_json_response($data, $http_code = 200) {
    http_response_code($http_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function log_error($message) {
    error_log("[".date("Y-m-d H:i:s")."] " . $message . "\n", 3, __DIR__ . '/../logs/error.log');
}
?>