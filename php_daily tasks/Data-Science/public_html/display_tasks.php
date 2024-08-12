<?php
$hostDetail = "localhost";
$userDetail = "id21804836_root";
$passwordDetail = 'P@ssw0rd';
$databaseDetail = "id21804836_projdata";

$connectionDetail = new mysqli($hostDetail, $userDetail, $passwordDetail, $databaseDetail);
if ($connectionDetail->connect_error) {
    die("Connection failed: " . $connectionDetail->connect_error);
}

$catID = $_GET['category_id'] ?? '';
$allTasks = isset($_GET['show_all']) && $_GET['show_all'];

$queryCategories = "SELECT id, name FROM categories ORDER BY name";
$resultCategories = $connectionDetail->query($queryCategories);

$taskQuery = "SELECT task_id, description, due_date, priority, status, categories.name AS category 
        FROM tasks 
        JOIN categories ON tasks.category_id = categories.id 
        WHERE tasks.status <> 'completed' ";

if (!$allTasks) {
    $taskQuery .= empty($catID) ? "AND tasks.due_date <= ? " : "AND tasks.category_id = ? ";
    $taskQuery .= "ORDER BY tasks.priority, tasks.due_date";
}

$taskStmt = $connectionDetail->prepare($taskQuery);
if (!$allTasks) {
    $taskParam = empty($catID) ? date('Y-m-d') : $catID;
    $taskStmt->bind_param(empty($catID) ? "s" : "i", $taskParam);
}

$taskStmt->execute();
$tasksResult = $taskStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Display</title>
    <style>
        body { 
            font-family: 'Verdana', sans-serif; 
            background: #e6f7ff;  
            color: #000000; 
            font-weight: bold;
            line-height: 1.6; 
            padding: 20px; 
            margin: 0;
        }
        form { 
            background: #ccf2ff;  
            padding: 15px; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        select, button { 
            padding: 10px 20px; 
            margin-right: 20px; 
            border-radius: 6px; 
            border: 2px solid #009fdf;  
            background: #b3e0ff;  
            color: #000000;
            font-weight: bold;
        }
        button:hover {
            background: #99d6ff;  
        }
        ul { 
            list-style: none; 
            padding: 0; 
        }
        li { 
            margin: 10px 0; 
            padding: 15px; 
            background: #ccf2ff;  
            border-left: 6px solid #007acc;  
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a { 
            color: #000000; 
            text-decoration: none;
            padding: 8px 15px; 
            border-radius: 6px;
            background: #e6f7ff;  
            border: 1px solid #009fdf;  
            font-weight: bold;
        }
        a:hover {
            background: #b3e0ff;  
        }
        .info {
            flex-grow: 1;
        }
        .delayed {
            color: #c0392b;  
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form id="category-form" action="display_tasks.php" method="get">
        <label for="category_id">Task Category:</label>
        <select id="category_id" name="category_id" onchange="this.form.submit()">
            <option value="">Show All</option>
            <?php while ($category = $resultCategories->fetch_assoc()): ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $catID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="show_all" value="1">Display All Tasks</button>
    </form>
    <ul id="tasks-list">
        <?php if ($tasksResult->num_rows > 0): ?>
            <?php while ($task = $tasksResult->fetch_assoc()): ?>
                <li>
                    <div class="info">
                        <?= htmlspecialchars($task['description']) ?> - Due: <?= $task['due_date'] ?>
                        - Priority: <?= $task['priority'] ?> - Category: <?= $task['category'] ?>
                        - Status: <?= $task['status'] ?>
                        <?php if ($task['due_date'] < date('Y-m-d')): ?>
                            <span class="delayed"> - Overdue</span>
                        <?php endif; ?>
                    </div>
                    <a href="edit_task.php?task_id=<?= $task['task_id'] ?>" style="margin-right: 10px;">Edit</a>
                    <a href="mark_completed.php?task_id=<?= $task['task_id'] ?>" onclick="return confirm('Are you sure you want to mark this as completed?')">Mark Complete</a>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>There are no tasks available.</li>
        <?php endif; ?>
    </ul>
</body>
</html>