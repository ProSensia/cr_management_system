<?php
require_once __DIR__ . '/../config.php';
$mysqli = db_connect();

// Add task
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && $_POST['action']==='add'){
    $title = $mysqli->real_escape_string($_POST['title']);
    $desc = $mysqli->real_escape_string($_POST['description']);
    $due_date = $mysqli->real_escape_string($_POST['due_date']);
    $due_time = $mysqli->real_escape_string($_POST['due_time']);
    $remind_before = intval($_POST['remind_before']);
    $mysqli->query("INSERT INTO tasks (title,description,due_date,due_time,remind_before_minutes) VALUES ('$title','$desc','$due_date','$due_time',$remind_before)");
    $task_id = $mysqli->insert_id;
    // attach all students by default
    $res = $mysqli->query('SELECT id FROM students');
    while($r = $res->fetch_assoc()){
        $sid = intval($r['id']);
        $mysqli->query("INSERT INTO task_recipients (task_id,student_id) VALUES ($task_id,$sid)");
    }
    header('Location: assignments.php');
    exit;
}

// Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM tasks WHERE id=$id");
    header('Location: assignments.php');
    exit;
}

$tasks = $mysqli->query('SELECT * FROM tasks ORDER BY due_date DESC');
?>
<!doctype html><html><head>
<title>Assignments - CRMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container py-4">
  <h1>Assignments & Quizzes</h1>
  <div class="row">
    <div class="col-md-5">
      <form method="post">
        <input type="hidden" name="action" value="add">
        <div class="mb-2"><input name="title" class="form-control" placeholder="Title" required></div>
        <div class="mb-2"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
        <div class="mb-2"><input type="date" name="due_date" class="form-control" required></div>
        <div class="mb-2"><input type="time" name="due_time" class="form-control" required></div>
        <div class="mb-2"><input type="number" name="remind_before" class="form-control" value="1440" placeholder="Remind before (minutes)"></div>
        <button class="btn btn-primary">Create Task</button>
      </form>
    </div>
    <div class="col-md-7">
      <h5>Tasks</h5>
      <table class="table">
        <thead><tr><th>#</th><th>Title</th><th>Due</th><th></th></tr></thead>
        <tbody>
        <?php while($t=$tasks->fetch_assoc()){ ?>
          <tr>
            <td><?=htmlspecialchars($t['id'])?></td>
            <td><?=htmlspecialchars($t['title'])?></td>
            <td><?=htmlspecialchars($t['due_date']).' '.htmlspecialchars($t['due_time'])?></td>
            <td>
              <a class="btn btn-sm btn-danger" href="?delete=<?=$t['id']?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <p><a href="index.php">&larr; Back</a></p>
</div>
</body></html>
