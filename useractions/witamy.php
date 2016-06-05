<?php
session_start();

if(!isset($_SESSION['udanarej']))
{
    header('Location: ../index.php');
    exit();
}
else
{
    unset($_SESSION['udanarej']);
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
        <p>Dziękujemy za rejestracje, możesz sie juz zalogować</p>
        <p><a href='../index.php'>Zaloguj się na swoje konto</a></p>             
        
    </center>
    </body>
</html>
