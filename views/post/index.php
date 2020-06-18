<?php
use App\Model\Post;

$title = 'My Blog';

$pdo = new PDO('mysql:dbname=blog;host=mysql', 'laradock', 'laradock', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$query = $pdo->query('SELECT * FROM post ORDER BY created_at DESC LIMIT 12');
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
?>

<h1>My Blog</h1>
<div class="row">
  <?php foreach ($posts as $post): ?>
  <div class="col-md-3">
  <?php require 'card.php'?>
  </div>
  <?php endforeach?>
</div>
