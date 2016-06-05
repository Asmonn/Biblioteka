<?php
session_start();

if(!isset($_SESSION['udanadod']))
{
    header('Location: user.php');
    exit();
}
else
{
    unset($_SESSION['udanadod']);
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Biblioteka Online</title>
    </head>
    <body bgcolor='silver'>
    <center>
        <p>Udało się dodać książkę</p>
        <p><a href='dodaj.php'>Dodaj inną książkę</a></p>             
        
    </center>
    </body>
</html>
