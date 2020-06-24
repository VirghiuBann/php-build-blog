<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;
use App\PaginateQuery;

$title = 'My Blog';

$pdo = Connection::getPDO();

$paginationQuery = new PaginateQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(*) FROM  post"
);
$posts = $paginationQuery->getItems(Post::class);
$postsByID = [];
foreach ($posts as $post) {
    $postsByID[$post->getID()] = $post;
}
// dd($posts);
// dd($postsByID);
$categories = $pdo->query(
    'SELECT c.*, pc.post_id
    FROM post_category pc
    JOIN category c ON c.id = pc.category_id
    WHERE pc.post_id IN (' . implode(',', array_keys($postsByID)) . ')'
)->fetchAll(PDO::FETCH_CLASS, Category::class);
// dd($categories);
foreach ($categories as $category) {
    $postsByID[$category->getPostID()]->addCategory($category);
    // dd($postsByID);
}
//dd($posts);
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