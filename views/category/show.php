<?php

use App\Connection;
use App\Model\{Category, Post};
use App\URL;

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

$currentPage = URL::getPositiveInt('page', 1);

$count = (int) $pdo
    ->query('SELECT COUNT(*) FROM  post_category WHERE category_id = ' . $category->getID())
    ->fetch(PDO::FETCH_NUM)[0];

$perPage = 12;
$pages = ceil($count / $perPage);
if ($currentPage > $pages) {
    throw new Exception('Page not exist');
}
$offset = $perPage * ($currentPage - 1);
$query = $pdo
    ->query("
            SELECT p.* 
            FROM post p
            JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = {$category->getID()}
            ORDER BY created_at DESC 
            LIMIT $perPage OFFSET $offset
        ");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
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
    <?php if ($currentPage > 1) : ?>
        <?php
        $l = $link;
        if ($currentPage > 2) $l = $link . '?page=' . ($currentPage - 1);
        ?>
        <a href="<?= $l ?>" class="btn btn-primary">Prev</a>
    <?php endif ?>

    <?php if ($currentPage < $pages) : ?>
        <a href="<?= $link ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary ml-auto">Next</a>
    <?php endif ?>
</div>