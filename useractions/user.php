<?php 
session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: ../index.php');
    exit();
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
        ?>
    <center>
        <p><a href="rezerwacja.php">Zarezerwuj książkę</a></p>
        <p><a href="historia.php">Przeglądaj swoją historie</a></p>
    </center>
    </body>
</html>
