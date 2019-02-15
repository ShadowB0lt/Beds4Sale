<?php
function connect()
{
    $host = "localhost";
    $user = "Beds4Sale";
    $pass = "pa$$";
    $db = "Beds4Sale";
    $conn = mysqli_connect($host, $user, $pass, $db);
    return $conn;
}

function subscribe($email)
{
    $conn = connect();
    $query = "INSERT INTO b4s_newsletter VALUES ('$email')";
    mysqli_query($conn, $query);
    mysqli_close($conn);
    header("Location: index.html");
}

function register($email,$fname,$sname,$pcode,$pass)
{
    $conn = connect();
    $query = "INSERT INTO b4s_customer VALUES('$email','$fname','$sname','$pcode','$pass')";
    mysqli_query($conn,$query);
    mysqli_close($conn);
    header("Location: index.html");
}

function login($email, $pass)
{
    $conn = connect();
    $query = "SELECT * FROM b4s_customer WHERE email = '$email' AND pass = '$pass'";
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    if (mysqli_num_rows($result)  == 1){
        session_start();
        
        $_SESSION['user'] = $email;
        header("Location: index.html");
    }
    else
    {
        $msg = "Your username/password was not recognized - try again!";
        echo "<script type='text/javascript'>
            alert('$msg');
            window.location = 'register.html';
            </script>
            ";
    }
}

function display_products(){
    $conn = connect();
    $query = "SELECT * FROM b4s_product";
    $results = mysqli_query($conn, $query);
    echo "<table><tr>
        <th>Product Name</th>
        <th>Image</th>
        <th>Description</th>
        <th>Price</th>
        <th>Order</th>
        </tr>";
     while ($row = mysqli_fetch_array($results)) {
        echo "<tr>
            <td>$row[name]</td>
            <td><img src='$row[imagepath]' width='200' height='100' /></td>
            <td>$row[description]</td>
            <td>£$row[price]</td>
            <td><form action='basket.php' method='post'>
            <input type='submit' class='button' value='Add To Basket' name='$row[pid]' />
            </form></td>
        </tr>";
     }
    echo "</table>";
    mysqli_close($conn);
}

function add_to_basket($pid){
    session_start();
    if (isset($_SESSION['basket'])){
        if (isset($_SESSION['basket'][$pid])){
            $_SESSION['basket'][$pid]++;
        }else{
            $_SESSION['basket'][$pid] = 1;
        }
    }else{
        $_SESSION['basket'] = array($pid => 1);
    }
        header("location: basket.html");
}

function logout()
{
    session_start();
    if(!isset($_SESSION['email']))
    {
        
        session_unset();
        header("Location:index.html");
    }
    else
    {
        header("Location:index.html");
    }
}

function updateBasket($pid,$quantity){
    session_start();
            if ($quantity > 0)
            {
                $_SESSION['basket'][$pid] = $quantity; 
            }
            else
            {
                unset($_SESSION['basket'][$pid]); 
                
                
            }
   
        
    header("Location: basket.html");
}

function display_basket()
{
    if( !isset($_SESSION['basket']) )
    {
        echo "<p>Your Basket is empty. Go to Products Page to order Items.</p>";
        return;
    }
    echo "<table><tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Sub-total</th>
    </tr>
    ";
    $conn = connect();
    $total = 0;
    foreach($_SESSION['basket'] as $key=>$value){
     $query = "SELECT name, price FROM b4s_product WHERE pid=$key";  
     $result = mysqli_query($conn, $query);
     $row = mysqli_fetch_array($result);
     echo "<tr>
         <td>$row[name]</td>
         <td>£$row[price]</td>
         <td><h3>$value</h3>
         <form action='updateBasket.php' method='post'><input type='hidden' name='ID' value='$key' /><input type='text' name='quantity' placeholder='Add 0 to remove Item' /><input type='submit' value='Update' class='button' /></form>

 </td>
         <td>£".number_format($value*$row['price'],2,'.','')."</td>
         </tr>";
        
        $total = $total + $row['price'] * $value;
    }
    echo "</table>";
    mysqli_close($conn);
    echo "<table class='ordertbl'><tr>
    <th>Total</th>
    <th>Order</th>
    </tr>
    <td>£". number_format($total, 2, '.', '') ."</td>
    <td><form action='orderconf.php' method='post'>
    <input type='submit' value='Order' class='button' />
    </form></td>
    </tr></table>";
    
    
}

function order()
{
    session_start();
    if ( !isset($_SESSION['user']) ){
        $msg = "You must Login with a registered account to order items";
       
        echo "<script type='text/javascript'>
            alert('$msg');
            window.location = 'register.html';
            </script>
            ";
    }
    $conn = connect();
    $query = "INSERT INTO b4s_order VALUES(NULL, '$_SESSION[user]')";
    mysqli_query($conn, $query);
    $oid = mysqli_insert_id($conn);
    
    foreach ($_SESSION['basket'] as $key=>$value) {
        $query = "INSERT INTO b4s_orderitems VALUES($oid, $key, $value)";
        mysqli_query($conn, $query);
    }
    unset($_SESSION['basket']);
    mysqli_close($conn);
    $msg = "Your order has been received.";
    echo "<script type='text/javascript'>
        alert('$msg');
        window.location = 'index.html';
        </script>
        ";
        
}

function displayOrder()
{
}

?>