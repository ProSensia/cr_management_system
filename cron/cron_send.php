<?php
// cron/cron_send.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/send_whatsapp.php';
$mysqli = db_connect();

$now = new DateTime('now', new DateTimeZone(date_default_timezone_get()));
// Find tasks where remind_before_minutes is set and it's time to remind
$res = $mysqli->query('SELECT * FROM tasks');
while($task = $res->fetch_assoc()){
    $due = DateTime::createFromFormat('Y-m-d H:i:s', $task['due_date'].' '.$task['due_time']);
    if(!$due) continue;
    $remind_before = intval($task['remind_before_minutes']);
    $remind_time = clone $due;
    $remind_time->sub(new DateInterval('PT' . $remind_before . 'M'));
    // if current time >= remind_time and <= due time, send reminders for recipients not sent yet
    if($now >= $remind_time && $now <= $due){
        $tr = $mysqli->query('SELECT tr.*, s.phone, s.name FROM task_recipients tr JOIN students s ON s.id=tr.student_id WHERE tr.task_id='.$task['id'].' AND tr.sent=0');
        while($r = $tr->fetch_assoc()){
            $to = $r['phone'];
            if(!$to) continue;
            $message = "Reminder: {$task['title']} is due on {$task['due_date']} at {$task['due_time']}.\nDetails: {$task['description']}";
            $resp = send_whatsapp_message($to, $message);
            // store as sent (basic)
            $stmt = $mysqli->prepare('UPDATE task_recipients SET sent=1, last_sent_at=NOW() WHERE id=?');
            $stmt->bind_param('i', $r['id']);
            $stmt->execute();
            // small pause to avoid API rate limits
            sleep(1);
        }
    }
}
echo "Cron run completed at " . date('c') . "\n";
