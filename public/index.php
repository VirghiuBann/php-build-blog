<?php
require __DIR__ . '/../vendor/autoload.php';
define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (isset($_GET['page']) && $_GET['page'] === '1') {
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if (!empty($query)) {
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('Location: ' . $uri);
    exit();
}

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', '/post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', '/category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]', '/post/show', 'post')
    ->get('/admin', '/admin/post/index', 'admin_posts')
    ->get('/admin/post/[i:id]', '/admin/post/edit', 'admin_post_edit')
    ->get('/admin/post/create', '/admin/post/create', 'admin_post_create')
    ->post('/admin/post/[i:id]/delete', '/admin/post/delete', 'admin_post_delete')
    ->run();
