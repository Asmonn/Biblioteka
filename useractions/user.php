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
        echo "<p>Witaj ".@$_SESSION['login'].'! [<a href="logout.php">Wyloguj się</a>]</p>';
        ?>
    </body>
</html>
