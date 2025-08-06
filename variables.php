<?php
$name = "ali";
echo $name."<br>";

$username =$_GET['your name'] ??'guest';
echo 'welcome'.$username.'<br>';


#variable and output with modification
$name="Ali Haider";
$status="Studying";

echo "<h1>My name is $name</h1><br>";
echo "<h2>currently i am $status</h2><br>";

#with single qoutes use .dot with variable
echo 'My name is'.$name. '<br>';
echo 'currently i am'.$status. '<br>';

#within double qoutes 
echo "My name is $name <br>";
echo "currently i am $status <br>";

#difference between single and doublw qoutes for string and variable
echo "My name is $name <br>";
echo 'My name is $name <br>';

#variable golbal and local scope
$a=5;
$b=4;

function sum(){
    global $a,$b;
    $c=$a+$b;
    echo"local variable.$c<br>";
    
}

sum();

var_dump($name);
var_dump($a);
var_dump($b);

?>

