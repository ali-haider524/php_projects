<?php
#different datatypes using var_dump function
$name="Ali";
$surname="Haider";
$age=20;
$height=5.5;
$subject=array("Computer","Math");

var_dump($name);
echo"$name.<br>";

var_dump($age);
echo"$age.<br>";

var_dump($height);
echo"$height.<br>";

var_dump($subject);
echo"$name.<br>";

#string functions
echo strtoupper($name);
echo str_replace("Ali","Hdr",$name);
echo "strtolower($name).<br>";
echo "trim($name).<br>";

#string concatenation
$conct=$name.$surname;
echo "$conct.<br>";
$conct="$name $surname";
echo "$conct.<br>";

#constant and variabels
$var="Ali";
echo "$var.<br>";
$var="Haider";
echo "$var.<br>";

define("price","45$");
echo price.'<br>';
// const price="35$"; will show error


?>