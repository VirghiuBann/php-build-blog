<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();

$title = "Admin";
$pdo = Connection::getPDO();
$link = $router->url('admin_posts');
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();

?>

<table class="table table-striped">
    <thead>
        <th>#</th>
        <th>Title</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?=$post->getID()?></td>
                <td><?=e($post->getName())?></td>
                <td>
                    <a href="<?=$router->url('admin_post_edit', ['id' => $post->getID()])?>" class="btn btn-primary">Edit</a>
                    <form action="<?=$router->url('admin_post_delete', ['id' => $post->getID()])?>"
                        method="POST" onsubmit="return confirm('Are you sure delete blog?')" style="display: inline;">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach?>
    </tbody>
</table>
<div class="d-flex justify-content-between my-4">
    <?=$pagination->prevLink($link)?>
    <?=$pagination->nextLink($link)?>
</div>