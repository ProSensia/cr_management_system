<?php
require_once __DIR__ . '/../config.php';
$mysqli = db_connect();

// Add student
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && $_POST['action']==='add'){
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $roll = $mysqli->real_escape_string($_POST['roll']);
    $batch = $mysqli->real_escape_string($_POST['batch']);
    $mysqli->query("INSERT INTO students (name,email,phone,roll_no,batch) VALUES ('$name','$email','$phone','$roll','$batch')");
    header('Location: students.php');
    exit;
}

// Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM students WHERE id=$id");
    header('Location: students.php');
    exit;
}

$students = $mysqli->query('SELECT * FROM students ORDER BY created_at DESC');
?>
<!doctype html>
<html><head>
<title>Students - CRMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container py-4">
  <h1>Students</h1>
  <div class="row">
    <div class="col-md-6">
      <form method="post">
        <input type="hidden" name="action" value="add">
        <div class="mb-2"><input name="name" class="form-control" placeholder="Full name" required></div>
        <div class="mb-2"><input name="email" class="form-control" placeholder="Email"></div>
        <div class="mb-2"><input name="phone" class="form-control" placeholder="Phone (include country code, e.g. +92...)"></div>
        <div class="mb-2"><input name="roll" class="form-control" placeholder="Roll no"></div>
        <div class="mb-2"><input name="batch" class="form-control" placeholder="Batch/Section"></div>
        <button class="btn btn-primary">Add Student</button>
      </form>
    </div>
    <div class="col-md-6">
      <h5>Student list</h5>
      <table class="table">
        <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Roll</th><th></th></tr></thead>
        <tbody>
        <?php while($s=$students->fetch_assoc()){ ?>
          <tr>
            <td><?=htmlspecialchars($s['id'])?></td>
            <td><?=htmlspecialchars($s['name'])?></td>
            <td><?=htmlspecialchars($s['phone'])?></td>
            <td><?=htmlspecialchars($s['roll_no'])?></td>
            <td><a class="btn btn-sm btn-danger" href="?delete=<?=$s['id']?>" onclick="return confirm('Delete?')">Delete</a></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <p><a href="index.php">&larr; Back</a></p>
</div>
</body></html>
