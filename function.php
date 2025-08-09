<?php

#variadic function in php
function item (...$x){
    $n=0;
    $len=count($x);
    for($i=0; $i<=$len; $i++){
        $n+= $x[$i];
        echo "$n <br>";
    }
    return $n;
}
echo 'list of item is:'.item(2,3,4,5,6).'<br>';

?>