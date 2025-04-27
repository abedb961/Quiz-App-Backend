<?php
include ('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_quiz'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO quizzes (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => 'Quiz Created!']);
    } else {
        echo json_encode(['error' => 'Error! Failed To Create Quiz.']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_quizzes'])) {
    $result = $conn->query("SELECT * FROM quizzes");
    $quizzes = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($quizzes);
}
?>