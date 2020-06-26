<?php

namespace App\Table;

use App\Model\Post;
use App\PaginateQuery;

final class PostTable extends Table
{
    protected $table = "post";
    protected $class = Post::class;

    public function update(Post $post): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name WHERE id = :id");
        $ok = $query->execute([
            'id' => $post->getID(),
            'name' => $post->getName(),
        ]);
        if ($ok === false) {
            throw new \Exception("This post {$post->getID()} cannot be updated from the {$this->table}.");
        }

    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new \Exception("This post $id cannot be removed from the {$this->table}.");
        }
    }

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
