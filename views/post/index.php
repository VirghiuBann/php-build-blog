<?php

use App\Connection;
use App\Model\Post;
use App\PaginateQuery;

$title = 'My Blog';

$pdo = Connection::getPDO();

$paginationQuery = new PaginateQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(*) FROM  post"
);
$posts = $paginationQuery->getItems(Post::class);
$link = $router->url('home');
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
    <?= $paginationQuery->prevLink($link) ?>
    <?= $paginationQuery->nextLink($link) ?>
</div>