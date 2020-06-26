<?php
use App\Connection;
use App\Table\PostTable;
use App\Validation\Validator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST)) {
    $v = new Validator($_POST);
    $v->rule('required', 'name');
    $v->rule('lengthBetween', 'name', 3, 200);
    $post->setName($_POST['name']);
    if ($v->validate()) {
        $postTable->update($post);
        $success = true;
    } else {
        $errors = $v->errors();
    }
}
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
    <div class="form-group">
        <label for="name">Title</label>
        <input type="text" class="form-control <?=isset($errors['name']) ? 'is-invalid' : ''?>" name="name" value="<?=e($post->getName())?> " required>
        <?php if (isset($errors['name'])): ?>
        <div class="invalid-feedback">
            <?=implode('<br>', $errors['name'])?>
        </div>
        <?php endif?>
    </div>
    <button class="btn btn-primary">Update</button>
</form>