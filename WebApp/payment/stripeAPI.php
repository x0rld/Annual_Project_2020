<?php
require_once __DIR__."/../session.php";
?>
<html>
<?php
echo "<title>Services - Flash Assistance</title>";
echo "<link href='https://fonts.googleapis.com/css?family=Playfair+Display&display=swap' rel='stylesheet'>";
echo "<link rel='stylesheet' href='../css/services.css'>";
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../images/logo_dark.png"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/stripe.css"/>
<title>Flash Assistance</title>
<script src="../javascript/header.js" defer></script>
<script src="../javascript/footer.js" defer></script>
<style>

</style>
    <body onload="checkFooter()">
<?php
include('../header.php');
$DbManager = App::getDb();
if(isset($_GET['express']) && $_GET['express'] == true){
    $q = $DbManager->query("SELECT * FROM orders WHERE status = 'express'");
    $res = $q->fetchAll();
    $finalprice = $res[0]['price'];
    $_SESSION['price'] = $finalprice;
}else if(isset($_GET['sub'])){
    $sub = $_GET['sub'];
    $finalprice = $_SESSION['price'];
}else{
    $finalprice = $_SESSION['price'];
}
?>

<h5><?php if(isset($_GET['return'])) {
        echo $_GET['return'] . "<br><br>";
        echo "<a href=\"../services.php\" class=\"btn btn-dark rounded-pill py-2 btn-block\" style='width: 15%;background-color: blue;'>See more of our Services</a>";

    }
?></h5>
<?php
if(isset($_GET['express']) && $_GET['express'] == true){
    echo "<form action='charge.php?express=true' method='post' id='payment-form'>";
}else if(isset($_GET['sub'])){
    echo "<form action='charge.php?sub=$sub' method='post' id='payment-form'>";
}else{
    echo "<form action='charge.php' method='post' id='payment-form'>";
}?>
        <div style="text-align: center;">
            <label for="card-element">Credit or debit card</label>
            <div style="width: 70%;margin-top: 3%;margin-bottom: 3%" id="card-element">
                <!-- a Stripe Element will be inserted here. -->
            </div>
            <!-- Used to display form errors -->
            <div id="card-errors"></div>
        </div>
        <button>Submit Payment</button>
    </form>
    <!-- The needed JS files -->
    <!-- JQUERY File -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/">    </script>
    <!-- Your JS File -->
    <script src="../javascript/charge.js"></script>

<?php
include ('../footer.php');
?>
    </body>
</html>


