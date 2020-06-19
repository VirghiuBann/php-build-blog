<?php

use App\Connection;
use App\Model\{Category, Post};

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);

/** @var Post|false */
$post = $query->fetch();

if ($post === false) {
    throw new Exception('Not Found');
}

if ($post->getSlug() !== $slug) {
    $url = $route->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

$query = $pdo->prepare('
    SELECT c.id, c.slug, c.name
    FROM post_category pc
    JOIN category c ON pc.category_id = c.id
    WHERE pc.post_id = :id');
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
$query->execute(['id' => $post->getID()]);
$categories = $query->fetchAll();
$title = "Post";
?>


<h1 class="card-title"><?= e($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($categories as $key => $category) :
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