<?php
// Assuming you have set your Page Access Token as an environment variable or directly in the code
define('PAGE_ACCESS_TOKEN', 'YOUR_PAGE_ACCESS_TOKEN');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $recipientId = 'RECIPIENT_ID'; // You need to set this to the recipient ID

    // Prepare the message data
    $data = [
        'recipient' => [
            'id' => $recipientId
        ],
        'message' => [
            'text' => $message
        ]
    ];

    // Send the message via the Messenger API
    $ch = curl_init("https://graph.facebook.com/v12.0/me/messages?access_token=" . PAGE_ACCESS_TOKEN);
    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check for success
    if (isset($responseData['message_id'])) {
        echo json_encode(['status' => 'success', 'message_id' => $responseData['message_id']]);
    } else {
        echo json_encode(['status' => 'error', 'error' => $responseData['error']['message'] ?? 'Failed to send message']);
    }
} else {
    echo json_encode(['status' => 'error', 'error' => 'Invalid request method']);
}