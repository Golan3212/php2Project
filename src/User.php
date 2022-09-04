<?php
// для пользователей: id, имя, фамилия;

class User {
    private int $id = 0;
    private string $name;
    private string $lastName;

public function __construct($id, $name, $lastName)
{
    $this->id = $id;
    $this->name = $name;
    $this->lastName = $lastName;
}


    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}