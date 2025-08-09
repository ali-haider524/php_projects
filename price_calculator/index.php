
<h2>Tax Calculator app</h2>
<form method="POST">
    product name:<input type="text" name="product_name" required><br><br> 
    product quantity:<input type="number" name="product_quantity" required><br><br> 
    product price:<input type="number" name="product_price" required><br><br>
    <button type="submit">Calculate</button>
</form>


<?php
#define constant value for tax rate its 5%=0.05
define("tax_rate",0.05);

#declare variables for calculation
$tax=0;
$total=0;
$grand_toatl=0;

#dynamic input from user
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    #user input
    $product_name=$_POST["product_name"];   
    $product_quantity=$_POST["product_quantity"];
    $product_price=$_POST["product_price"];

    #actual tax calculation
    $total=$product_quantity*$product_price;
    $tax=$total*tax_rate;
    $grand_toatl=$total+$tax;

    #display final calculation
    echo "Bill Summary.<br>";
    echo 'Product name:'.$product_name.'<br>';
    echo 'Total price:'.$total.'<br>';
    echo 'Grand Total:'.$grand_toatl.'<br>';
}

?>



