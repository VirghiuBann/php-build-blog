<?php

namespace App;

use Exception;
use \PDO;
use App\URL;

class PaginateQuery
{
    private $query;
    private $queryCount;
    private $pdo = null;
    private $perPage = 12;
    private $count;
    private $items;

    public function __construct(
        string $query,
        string $queryCount,
        ?PDO $pdo = null,
        int $perPage = 12
    ) {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: Connection::getPDO();
        $this->perPage = $perPage;
    }

    public function getItems(string $classMapping): array
    {
        if (!$this->items) {
            $currentPage = URL::getPositiveInt('page', 1);
            $pages = $this->getPages();
            if ($currentPage > $pages) {
                throw new Exception('This page not exist!');
            }
            $offset = $this->perPage * ($currentPage - 1);
            return $this->pdo->query(
                $this->query . " LIMIT {$this->perPage} OFFSET $offset"
            )
                ->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;
    }

    public function prevLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary">Prev</a>
HTML;
    }

    public function nextLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary ml-auto">Prev</a>
HTML;
    }

    public function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }

    public function getPages(): int
    {
        if ($this->count === null) {
            $this->count = (int) $this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
        }
        return ceil($this->count / $this->perPage);
    }
}