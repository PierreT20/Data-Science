<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin: 20px 0;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        iframe {
            border: none;
        }
        .author-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>ToDo List Application</h1>

    <h2>Add New Category</h2>
    <form action="add_category.php" method="post" id="categoryForm">
        <input type="text" name="category_name" placeholder="Category Name" required>
        <button type="submit" name="action" value="addCategory">Add Category</button>
    </form>
    
    <p><a href="edit_categories.php">Edit Categories</a></p>

    <h2>Add Task</h2>
    <form action="tasks.php" method="post" id="taskForm">
        <input type="text" name="description" placeholder="Task Description" required>
        <input type="date" name="due_date" required>
        <select name="category_id">
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

            $result = $conn->query("SELECT id, name FROM categories");
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
            }
            $conn->close();
            ?>
        </select>
        <select name="priority">
            <option value="">No Priority</option>
            <option value="1">1 (Highest)</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4 (Lowest)</option>
        </select>
        <select name="status">
            <option value="active">Active</option>
            <option value="completed">Completed</option>
        </select>
        <button type="submit" name="action" value="addTask">Add Task</button>
    </form>
    
    <h2>Tasks</h2>
    <iframe src="display_tasks.php" style="width:100%; height:300px;"></iframe>

    <div class="author-link">
        <a href="authors.html">Meet Our Authors</a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            ['categoryForm', 'taskForm'].forEach(function(formId) {
                var form = document.getElementById(formId);
                var randomColor = '#' + Math.floor(Math.random()*16777215).toString(16);
                var borderColor = '#' + Math.floor(Math.random()*16777215).toString(16);
                var borderWidth = Math.floor(Math.random() * 5 + 1) + 'px';
                form.style.backgroundColor = randomColor;
                form.style.border = borderWidth + ' solid ' + borderColor;
            });
        });
    </script>
</body>
</html>
