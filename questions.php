<?php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_question'])) {
    $quiz_id = $_POST['quiz_id'];
    $question = $_POST['question'];
    $possible_answers = json_encode($_POST['possible_answers']);
    $correct_answer = $_POST['correct_answer'];

    $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question, possible_answers, correct_answer) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $quiz_id, $question, $possible_answers, $correct_answer);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => 'Question Created Successfully!']);
    } else {
        echo json_encode(['error' => 'Error, Question Failed To Be Created.']);
    }
}
?>