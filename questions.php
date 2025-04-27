<?php
require_once 'db.php';
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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $row['possible_answers'] = json_decode($row['possible_answers'], true);
        $questions[] = $row;
    }
    echo json_encode($questions);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_question'])) {
    $question_id = $_POST['question_id'];
    $question = $_POST['question'];
    $possible_answers = json_encode($_POST['possible_answerss']);
    $correct_answer = $_POST['correct_answer'];

    $stmt = $conn->prepare("UPDATE questions SET question = ?, possible_answers = ?, correct_answer = ? WHERE id = ?");
    $stmt->bind_param("sssi", $question, $possible_answers, $correct_answer, $question_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => 'Question Updated Successfully']);
    } else {
        echo json_encode(['error' => 'Error, Question Failed to be Updated.']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_question'])) {
    $question_id = $_POST['question_id'];

    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => 'Question Deleted successfully!']);
    } else {
        echo json_encode(['error' => 'Error, Question Failed to be Deleted.']);
    }
}
$conn->close();
?>