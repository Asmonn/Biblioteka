<?php
session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: ../index.php');
    exit();
}

require_once '../database/config.php';

$login = $_SESSION['login'];

@$tyt = $_POST['tyt'];
@$aut = $_POST['aut'];
@$rok = $_POST['rok'];

if(isset($_POST['tyt']))
{
    $connect = @new mysqli($host, $dbuser, $dbpass, $dbname);
    if ($connect->connect_errno != 0) 
    {
        echo "Error: ".$connect->connect_errno;
    }
    elseif ($connect->query("insert into books (tytul, autor, rokwyd, stan) values ('$tyt', '$aut', '$rok', 'wolny')")) 
        {
            $_SESSION['udanadod']=true;
            header('Location: udanadod.php');
        } else
        {
            echo 'Błąd serwera';
        }    
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Biblioteka online</title>        
    </head>
    <body bgcolor='silver'>
        <center>
    <p><h2>Dodawanie książek</h2></p>
    <form method="post">
        <p>Tytuł: <br>
            <input type="text" name="tyt"/></p>
        <p>Autor: <br>
            <input type="text" name="aut"/></p>
        <p>Rok wydania: <br>
            <input type="text" name="rok"/></p>
        <p><input type="submit" name="send" value="Dodaj"></p>
        <p><a href="user.php">Powrót na strone główną</a></p>
        </center>
    </form>
    </body>
</html>
