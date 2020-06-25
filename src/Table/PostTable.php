<?php

namespace App\Table;

use App\PaginateQuery;
use App\Model\Post;

final class PostTable extends Table
{
    protected $table = "post";
    protected $class = Post::class;

    public function findPaginated()
    {
        $paginatedQuery = new PaginateQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(*) FROM  post",
            $this->pdo
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    public function findPaginateForCategory(int $categoryID)
    {
        $paginatedQuery = new PaginateQuery(
            "SELECT p.* 
            FROM post p
            JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = {$categoryID}
            ORDER BY created_at DESC",
            "SELECT COUNT(*) 
            FROM post_category
            WHERE category_id = {$categoryID}"
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];
    }
}
