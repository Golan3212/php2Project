<?php
//комментариев: id, id автора, id статьи, текст


class Comment {
    private int $id;
    private User $authorId;
    private Article $titleId;
    private string $text;

    public function __construct(User $authorId, Article $titleId, string $text)
    {
        $this->authorId = $authorId; 
        $this->titleId = $titleId; 
        $this->text = $text;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}