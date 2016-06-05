<?php 
session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: ../index.php');
    exit();
}

require_once '../database/config.php';

$login = $_SESSION['login'];

$connect = @new mysqli ($host, $dbuser, $dbpass, $dbname);
if ($connect->connect_errno != 0) 
{
    echo "Error: ".$connect->connect_errno;
}
 else 
{
    $result1 = @$connect->query("select rola from userdata where login = '$login'");
    $row1 = $result1->fetch_row();         
    //$connect->close();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Biblioteka Online</title>
    </head>
    <body bgcolor='silver'>
        <?php
        echo "<center><p><h2>Witaj ".@$_SESSION['login'].'! [<a href="logout.php">Wyloguj się</a>]</h2></p></center>';
        
        if($row1[0] == "admin")
        {
            echo '<center><p><a href="dodaj.php">Dodaj książkę</a></p></center>';
            echo '<center><p><a href="rezerwacja.php">Zarezerwuj książkę</a></p></center>';
            echo '<center><p><a href="historia.php">Przeglądaj swoją historie</a></p></center>';
        } else {
            echo '<center><p><a href="rezerwacja.php">Zarezerwuj książkę</a></p></center>';
            echo '<center><p><a href="historia.php">Przeglądaj swoją historie</a></p></center>';
        }   
        ?>   
    </body>
</html>
