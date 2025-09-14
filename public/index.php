<?php
require_once __DIR__ . '/../config.php';
$mysqli = db_connect();

// Quick counts
$students = $mysqli->query('SELECT COUNT(*) as c FROM students')->fetch_assoc()['c'];
$tasks = $mysqli->query('SELECT COUNT(*) as c FROM tasks')->fetch_assoc()['c'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>CR Management Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h1 class="mb-3">CR Management System</h1>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Students</h5>
          <p class="display-6"><?=htmlspecialchars($students)?></p>
          <a class="btn btn-primary" href="students.php">Manage Students</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Assignments / Quizzes</h5>
          <p class="display-6"><?=htmlspecialchars($tasks)?></p>
          <a class="btn btn-primary" href="assignments.php">Manage Tasks</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Send Test Message</h5>
          <p class="small">Send a quick WhatsApp to a number (for testing)</p>
          <form method="post" action="send_test.php">
            <div class="mb-2"><input name="to" class="form-control" placeholder="whatsapp:+92300XXXXXXX"></div>
            <div class="mb-2"><input name="message" class="form-control" placeholder="Hello students!"></div>
            <button class="btn btn-outline-success">Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <hr class="my-4">
  <h4>Recent tasks</h4>
  <div class="list-group">
  <?php
  $res = $mysqli->query('SELECT * FROM tasks ORDER BY due_date ASC LIMIT 5');
  while($row = $res->fetch_assoc()){
      echo '<div class="list-group-item">';
      echo '<div class="d-flex w-100 justify-content-between"><h5 class="mb-1">'.htmlspecialchars($row['title']).'</h5><small>'.htmlspecialchars($row['due_date']).' '.htmlspecialchars($row['due_time']).'</small></div>';
      echo '<p class="mb-1">'.htmlspecialchars(substr($row['description'],0,150)).'</p>';
      echo '</div>';
  }
  ?>
  </div>

</div>
</body>
</html>
