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
/** @var Post[] */
$posts = $paginationQuery->getItems(Post::class);

$postsByID = [];
foreach ($posts as $post) {
    $postsByID[$post->getID()] = $post;
}
$categories = $pdo->query(
    'SELECT c.*, pc.post_id
    FROM post_category pc
    JOIN category c ON c.id = pc.category_id
    WHERE pc.post_id IN (' . implode(',', array_keys($postsByID)) . ')'
)->fetchAll(PDO::FETCH_CLASS, Category::class);
foreach ($categories as $category) {
    $postsByID[$category->getPostID()]->addCategory($category);
}

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