<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$category = (new CategoryTable($pdo))->find($id);

if ($category->getSlug() !== $slug) {
    $url = $route->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}
$title = "Category {$category->getName()}";

[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginateForCategory($category->getID());

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
    <?= $paginatedQuery->prevLink($link) ?>
    <?= $paginatedQuery->nextLink($link) ?>
</div>