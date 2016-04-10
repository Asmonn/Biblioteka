<?php

session_start();

if(!isset($_POST['login']) || !isset($_POST['haslo']))
{
    header('Location: ../index.php');
    exit();
}

require_once '../database/config.php';

$connect = @new mysqli ($host, $dbuser, $dbpass, $dbname);

if($connect->connect_errno!=0)
{
    echo "Error: ".$connect->connect_errno;
}
else
{
    $user = @$_POST['login'];
    $pass = @$_POST['haslo'];
    
    $user = htmlentities($user, ENT_QUOTES, "UTF-8");    
        
    if($result = @$connect->query(
            sprintf("select * from userdata where login = '%s'",
            mysqli_real_escape_string($connect,$user))))
    {
       $row = $result->num_rows;
       if($row > 0)
       {
           $wiersz = $result->fetch_assoc();
           
           if(password_verify($pass, $wiersz['haslo']))
           {
           
            $_SESSION['zalogowany'] = true;

            $_SESSION['login'] = $wiersz['login'];
            $_SESSION['pass'] = $wiersz['haslo'];

            unset($_SESSION['blad']);
            $result->free_result();
            header('Location: user.php');
           }
           else
            {
                $_SESSION['blad'] = '<span style = "color:red"> Nieprawidłowy login lub hasło</span>';
                header('Location: ../index.php');
            }
       }
       else
       {
           $_SESSION['blad'] = '<span style = "color:red"> Nieprawidłowy login lub hasło</span>';
           header('Location: ../index.php');
       }
    }
    
    $connect->close();
}
