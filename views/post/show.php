<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if ($post->getSlug() !== $slug) {
    $url = $route->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

$title = "Post";
?>

<h1 class="card-title"><?= e($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($post->getCategories() as $key => $category) :
    if ($key > 0) :
        echo ', ';
    endif;
    $category_url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);
?><a href="<?= $category_url ?>"> <?= e($category->getName()) ?></a>
<?php endforeach ?>
<p><?= $post->getFormattedContent() ?></p>
<p>
    <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>" class="btn btn-primary">More...</a>
</p>