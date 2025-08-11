<?php
$nameErr=$emailErr="";
$name=$email="";

#Php Post Method for from handling
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $user_name=$_POST['name'];
    if(empty ($user_name)){
        $nameErr="Name is Required";
    }
    else{
      $user_name=test_input($user_name);
      if(!preg_match("/^[a-zA-Z ]*$/",$user_name)){
        $nameErr="Only letter are allowed";
       
      }
       echo "Entered name is : $user_name <br>";

    }

    $user_email=$_POST['email'];
    if(empty ($user_email)){
        $emailErr="Email is Required";
    }
    else{
      $user_email=test_input($user_email);
      if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
    $emailErr = "Invalid email format";
    }
      echo "Entered Email is : $user_email <br>";

    }

}


#Function to remove whitespaces and convert to html characters
function test_input($data){
    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}
?>

<h2 align="center">Php Form Validation</h2>
<form method="POSt" align="center">
    Name:<input type="text" name="name" value="<?php echo $name; ?>">
    <span style="color:red;"><?php echo $nameErr; ?></span>
     <br><br> 
    Email:<input type="text" name="email" value="<?php echo $email; ?>"> 
    <span style="color:red;"><?php echo $emailErr; ?></span>
    <br><br> 
    <button type="submit">Submit</button>

</form>
