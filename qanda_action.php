<?php
// ajax handler for deactivating a Q&A entry
session_start();
header('Content-Type: application/json'); // Set response type to JSON
require_once 'class/dbConnect.php';
require_once 'class/QandaInfo.php';
require_once 'config/config.php';
$response = [
    'status' => 'error',
    'message' => 'An unknown error occurred.'
];

$responseCodeValue = 400;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deactivate') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);
        $newStatus = 0; // inactive status

        $db = new dbConnect();
        $conn = $db->connect();

        $qandaObj = new QandaInfo();

        if ($qandaObj->updateStatus($conn, $id, $newStatus)) {
            $response['status'] = 'success';
            $responseCodeValue = 200;
            $response['message'] = 'Question hidden successfully.';
        } else {
            $response['message'] = 'Failed to hide the question. Please try again.';
        }
    } else {
        $response['message'] = 'Invalid ID provided.';
    }
} else {
    $response['message'] = 'Invalid request method or action.';
}

http_response_code($responseCodeValue);
echo json_encode($response);
exit;