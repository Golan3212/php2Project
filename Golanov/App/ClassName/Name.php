<?php

namespace App\ClassName;

class ClassName_Name
{
private string $name;

 public function __construct( string $name)
 {
    $this->name = $name; 
 }

 public function sayName()
 {
    return $this->name;
 }

}