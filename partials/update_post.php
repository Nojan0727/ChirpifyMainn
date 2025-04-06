<?php
session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $index = isset($data['index']) ? (int)$data['index'] : -1;
    $action = isset($data['action']) ? $data['action'] : '';

    if ($index < 0 || !isset($_SESSION['posts'][$index])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid post index']);
        exit();
    }

    if ($action === 'like') {
        $_SESSION['posts'][$index]['likes'] = ($_SESSION['posts'][$index]['likes'] ?? 0) + 1;
        $newCount = $_SESSION['posts'][$index]['likes'];
        echo json_encode(['success' => true, 'count' => $newCount]);
    } elseif ($action === 'repost') {
        $_SESSION['posts'][$index]['reposts'] = ($_SESSION['posts'][$index]['reposts'] ?? 0) + 1;
        $newCount = $_SESSION['posts'][$index]['reposts'];
        echo json_encode(['success' => true, 'count' => $newCount]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>