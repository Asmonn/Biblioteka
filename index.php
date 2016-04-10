<?php
session_start();

if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany'] == true))
{
    header('Location: ./useractions/user.php');
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
    <center>
        <p><h2>Logowanie</h2></p>
        <p><a href='./useractions/rejestracja.php'>Rejestracja - załóż darmowe konto</a></p>
        <form action='./useractions/zaloguj.php' method="post">
        <p>Login<br>
            <input type="text" name="login"/></p>
        <p>Hasło<br>
            <input type="password" name="haslo"/></p>
        <p><input type="submit" name="send" value="Zaloguj"></p>
        </form>
        
        <?php
        if(isset($_SESSION['blad']))
        {
            echo $_SESSION['blad'];
        }
        ?>
    </center>
    </body>
</html>
