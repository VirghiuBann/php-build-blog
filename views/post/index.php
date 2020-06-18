<?php

use App\Model\Post;

$title = 'My Blog';

$pdo = new PDO('mysql:dbname=blog;host=mysql', 'laradock', 'laradock', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$page = $_GET['page'] ?? 1;

if (!filter_var($page, FILTER_VALIDATE_INT)) {
  throw new Exception('Invalid number page!');
}
if ($page === '1') {
  header('Location: ' . $router->url('home'));
  http_response_code(301);
  exit();
}

$currentPage = (int) $page;
if ($currentPage <= 0) {
  throw new Exception('Invalid page');
}
$count = (int) $pdo->query('SELECT COUNT(*) FROM  post')->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
$pages = ceil($count / $perPage);
if ($currentPage > $pages) {
  throw new Exception('Page not exist');
}
$offset = $perPage * ($currentPage - 1);
$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
?>

<h1>My Blog</h1>
<div class="row">
  <?php foreach ($posts as $post) : ?>
    <div class="col-md-3">
      <?php require 'card.php' ?>
    </div>
  <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
  <?php if ($currentPage > 1) : ?>
    <?php
    $link = $router->url('home');
    if ($currentPage > 2) {
      $link .= '?page=' . ($currentPage - 1);
    }
    ?>
    <a href="<?= $link ?>" class="btn btn-primary">Prev</a>
  <?php endif ?>

  <?php if ($currentPage < $pages) : ?>
    <a href="<?= $router->url('home') ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary ml-auto">Next</a>
  <?php endif ?>
</div>