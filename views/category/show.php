<?php

use App\Connection;
use App\Model\{Category, Post};
use App\PaginateQuery;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare('SELECT * FROM category WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);

/** @var Post|false */
$category = $query->fetch();

if ($category === false) {
    throw new Exception('Not Found');
}

if ($category->getSlug() !== $slug) {
    $url = $route->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}
$title = "Category {$category->getName()}";
$paginationQuery = new PaginateQuery(
    "SELECT p.* 
            FROM post p
            JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = {$category->getID()}
            ORDER BY created_at DESC",
    "SELECT COUNT(*) 
            FROM post_category
            WHERE category_id = {$category->getID()}"
);
$posts = $paginationQuery->getItems(Post::class);
$posts = $paginationQuery->getItems(Post::class);
$posts = $paginationQuery->getItems(Post::class);
$posts = $paginationQuery->getItems(Post::class);
///dd($posts);
$link = $router->url('category', [
    'id' => $category->getID(),
    'slug' => $category->getSlug()
]);
?>
<h1><?= $title ?></h1>

<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) . '/post/card.php' ?>
        </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginationQuery->prevLink($link) ?>
    <?= $paginationQuery->nextLink($link) ?>
</div>