<?php

#while loop in php 
echo "While loop result<br>";
$i=0;
while($i<6){

    echo "current value of i is : $i <br>";
    $i++;
}
echo"<br><br>";


#do-while loop in php
echo "Do While loop result <br>";
$j=0;
do{
 
    echo "current value of i is : $j <br>";
    $j++;
}
while($j<6);
echo"<br><br>";


#for loop in php
echo "For loop result <br>";
for ($i=0; $i<6; $i++){
    echo "current value of i is : $i <br>";
}
echo"<br><br>";

#For each loop with key values in array
echo "Foreach loop result <br>";
$result=array("English"=>"95","Computer"=>"97","OOp"=>"85");

foreach ($result as $x => $y){
    echo "Marks of $x : $y <br>";
}
echo"<br><br>";


#break statement in loop
echo "Break statement <br>";
for ($i=0; $i<6; $i++){
    if($i==3){
        break;
    }
    echo "current value of i is : $i <br>";
}
echo"<br><br>";


#Continue statement in loop
echo "continue statement <br>";
for ($i=0; $i<6; $i++){
    if($i==3){
        continue;
    }
    echo "current value of i is : $i <br>";
}
echo"<br><br>";


?>