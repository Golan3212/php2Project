<?php

use App\ClassName\ClassName_Name; 
use App\package_name\ClassName\ClassName_Name2;
use App\ClassLoader\ClassLoader;


spl_autoload_register(
    function (string $class)
    {
        // DIRECTORY SELECTOR почему-то не работает тут, не смог выявить причину, поэтому поставил слэш так
        $path = str_replace('\\', '/', $class);
        // создал массив, в который буду записывать токены, разделителем является "/"
        $arr = [];
        $tok = strtok($path, "/");
        // заполняю массив токенами
        while ($tok !== false) {
                $arr[] = $tok;
                $tok = strtok("/");                  
        }
        // Создаю условие, что, если в последнем элементе нет "_", то конечный путь записываю через "/"
        if(strripos(end($arr), '_') === false){      
            $res = implode("/", $arr);
        // Иначе извлекаю последний элемент массива, меняю "_" на "/", пушу его заместо последнего элемента и объединяю с сепаратором    
        }else{
            $path = str_replace('_', '/', array_pop($arr)); 
            array_pop($arr) xor array_push($arr, $path);    
            $res = implode("/", array_unique($arr));
        }  
        require "$res.php";
        
    }
);

$name = new ClassName_Name ("andrey");
$name2 = new ClassName_Name2("egor");
echo $name->sayName();
$load = new ClassLoader("loaded");
$load->saySmth();
echo $name2->sayName();



