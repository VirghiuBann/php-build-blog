<?php
use App\Connection;
use App\HTML\Form;
use App\Table\PostTable;
use App\Validation\Validator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST)) {
    $v = new Validator($_POST);
    $v->rule('required', ['name', 'slug']);
    $v->rule('lengthBetween', ['name', 'slug'], 3, 200);
    $v->rule('required', ['name', 'content']);
    $v->rule('required', ['name', 'created_at']);

    $post->setName($_POST['name'])
        ->setContent($_POST['content'])
        ->setSlug($_POST['slug'])
        ->setCreatedAt($_POST['created_at']);

    if ($v->validate()) {
        $postTable->update($post);
        $success = true;
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        Updated post successfully
    </div>
<?php endif?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        Not updated
    </div>
<?php endif?>

<h1>Edit post <?=e($post->getName())?></h1>
<form action="" method="post">
    <?=$form->input('name', 'Title')?>
    <?=$form->input('slug', 'URL')?>
    <?=$form->textarea('content', 'Content')?>
    <?=$form->input('created_at', 'Created')?>
    <button class="btn btn-primary">Update</button>
</form>