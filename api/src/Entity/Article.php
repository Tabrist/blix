<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]

class Article {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]

    private $id;

    #[ORM\Column(type: 'string', length: 80)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    /**
     * Converts and returns current article object to an array.
     * 
     * @param $ignores | requires to be an array with string values matching the user object its private property names.
     */
    public function convertToArray(array $ignores = []) {
        $article = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
        ];

        for ($i = 0; $i < count($ignores); $i++) {
            if (array_key_exists($ignores[$i], $article)) {
                unset($article[$ignores[$i]]);
            }
        }

        return $article;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function setImage(string $image): self {
        $this->image = $image;

        return $this;
    }

}
