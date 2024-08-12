<?php
$servername = "localhost";
$username = "id21804836_root";
$password = 'P@ssw0rd';
$dbname = "id21804836_projdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $conn->prepare("UPDATE tasks SET status = 'completed' WHERE task_id = ?");
    $stmt->bind_param("i", $task_id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: display_tasks.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No task ID provided.";
}

$conn->close();
?>
