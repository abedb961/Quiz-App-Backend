<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_score'])) {
    $user_id = $_POST['user_id'];
    $quiz_id = $_POST['quiz_id'];
    $score = $_POST['score'];

    $stmt = $conn->prepare("INSERT INTO scores (user_id, quiz_id, score) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $quiz_id, $score);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => 'Score Submitted Successfully!']);
    } else {
        echo json_encode(['error' => 'Error, Score Failed To Be Submitted.']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $stmt = $conn->prepare("SELECT * FROM scores WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $scores = [];
    while ($row = $result->fetch_assoc()) {
        $scores[] = $row;
    }
    echo json_encode($scores);
}
?>