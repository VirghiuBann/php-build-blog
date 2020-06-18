<?php
namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post
{
    private $id;
    private $name;
    private $content;
    private $slug;
    private $created_at;
    private $categories = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getExtractText(): ?string
    {
        if ($this->content === null) {
            return null;
        }
        return nl2br(htmlentities(Text::extractText($this->content, 60)));
    }

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getID(): ?int
    {
        return $this->id;
    }
}