<?php
$db = new PDO('sqlite:/var/www/data/notes.db');
$db->exec("CREATE TABLE IF NOT EXISTS notes (id INTEGER PRIMARY KEY, text TEXT, created TEXT)");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['text'])) {
    $stmt = $db->prepare("INSERT INTO notes (text, created) VALUES (?, datetime('now','localtime'))");
    $stmt->execute([$_POST['text']]);
    header('Location: index.php'); exit;
}
$notes = $db->query("SELECT * FROM notes ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Notes</title></head>
<body style="font-family:sans-serif;max-width:600px;margin:40px auto">
<h1>Notes</h1>
<form method="post">
  <input name="text" placeholder="Write something" style="width:70%">
  <button>Save</button>
</form>
<ul>
<?php foreach ($notes as $n): ?>
  <li><?= htmlspecialchars($n['text']) ?> <small>(<?= $n['created'] ?>)</small></li>
<?php endforeach; ?>
</ul>
</body></html>
