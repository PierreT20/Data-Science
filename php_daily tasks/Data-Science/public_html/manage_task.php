<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Update Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Task Management</h1>

    <form action="tasks.php" method="post">
        <div>
            <label for="description">Task Description:</label>
            <input type="text" id="description" name="description" required placeholder="Enter task description">
        </div>
        <div>
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" required>
        </div>
        <div>
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id">
                <option value="">No Category</option>
                <?php
                $servername = "localhost";
                $username = "id21804836_root";
                $password = 'P@ssw0rd';
                $dbname = "id21804836_projdata";
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, name FROM categories ORDER BY name";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div>
            <label for="priority">Priority:</label>
            <select name="priority" id="priority">
                <option value="">No Priority</option>
                <option value="1">1 (Highest)</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4 (Lowest)</option>
            </select>
        </div>
        <div>
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="active">Active</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <button type="submit">Submit Task</button>
    </form>
</body>
</html>
