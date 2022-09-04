<?php
//статей: id, id автора, заголовок, текст;


class Article {
    private int $id;
    private User $authorId;
    private string $title;
    private string $text;

    public function __construct(User $authorId, string $title, string $text) 
    {
       $this->authorId = $authorId; 
       $this->title = $title; 
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

    public function __toString()
    {
        return $this->title . "\n" . $this->text;
    }

    /**
     * Get the value of authorId
     */ 
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set the value of authorId
     *
     * @return  self
     */ 
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }
}