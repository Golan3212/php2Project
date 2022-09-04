<?php

namespace App\ClassLoader;

class ClassLoader
{
private string $load;

 public function __construct( string $load)
 {
    $this->load = $load; 
 }

 public function saySmth()
 {
    echo "$this->load!\n";
 }

}
