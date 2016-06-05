<?php

session_start();

if(isset($_POST['email']))
{
    $wszystko_ok = true;
    
    $login = $_POST['login'];
    
    if((strlen($login)) < 3 || (strlen($login) > 20))
    {
        $wszystko_ok = false;
        $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków";
    }
    
    if(ctype_alnum($login) == false)
    {
        $wszystko_ok = false;
        $_SESSION['e_login'] = "Login może posiadać tylko litery i cyfry (bez polskich znaków)";
    }
    
    $email = $_POST['email'];
    $email_s = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if((filter_var($email_s, FILTER_VALIDATE_EMAIL) == false) || ($email_s != $email))
    {
        $wszystko_ok = false;
        $_SESSION['e_email'] = "Podaj poprawny adres e-mail";
    }
    
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];
    
    if((strlen($haslo1)) < 8 || (strlen($haslo1) > 20))
    {
        $wszystko_ok = false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków";
    }
    
    if($haslo1 != $haslo2)
    {
        $wszystko_ok = false;
        $_SESSION['e_haslo'] = "Źle powtórzone hasło";
    }
    
    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
    
    $sekret = "6LdJih0TAAAAAAYfIW0KO_F7YtoRALI8k4V23vQ7";
    
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
    
    $odpowiedz = json_decode($sprawdz);
    
    if($odpowiedz->success==false)
    {
        $wszystko_ok = false;
        $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem";
    }
    
    require_once '../database/config.php';
    
    try
    {
        $connect = @new mysqli ($host, $dbuser, $dbpass, $dbname);
        if($connect->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            $result = $connect->query("select login from userdata where email = '$email'");
            
            if(!$result) throw new Exception($connect->error);
            
            $ile_maili = $result->num_rows;
            if($ile_maili > 0)
            {
                $wszystko_ok = false;
                $_SESSION['e_email'] = "Istnieje juz konto z takim adresem e-mail";
            }
            
            $result = $connect->query("select login from userdata where login = '$login'");
            
            if(!$result) throw new Exception($connect->error);
            
            $ile_loginow = $result->num_rows;
            if($ile_loginow > 0)
            {
                $wszystko_ok = false;
                $_SESSION['e_login'] = "Ten login jest juz zajęty";
            }
            
            if($wszystko_ok == true)
            {
                if($connect->query("insert into userdata values ('$login', 'xxxx', 'yyyy', '$email', '$haslo_hash', 'user')"))
                {
                    $_SESSION['udanarej']=true;
                    header('Location: witamy.php');
                }
                else
                {
                   throw new Exception($connect->error);
                }
            }
            
            $connect->close();
        }
    } catch (Exception $ex) {
        echo 'Błąd serwera';
    }
     
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <title>Biblioteka online</title> 
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <style>
            .error
            {
                color:red;
                margin-top: 10px;
                margin-bottom: 10px;
            }            
        </style>
    </head>
    <body bgcolor='silver'>
        <center>
        <div class="container">
            <div class="row">
                <div class="col-lg-150 col-md-150 col-sm-150 col-xs-150">    
        <p><h2>Rejestracja</h2></p>
        <form method="post">
            <p>Login: <br>
                <input type="text" name="login"/></p>
            <?php
            if(isset($_SESSION['e_login']))
            {
                echo '<div class = "error">'.$_SESSION['e_login'].'</div>';
                unset($_SESSION['e_login']);
            }
            ?>
            <p>Email: <br>
                <input type="text" name="email"/></p>
            <?php
            if(isset($_SESSION['e_email']))
            {
                echo '<div class = "error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            }
            ?>
            <p>Hasło: <br>
                <input type="password" name="haslo1"/></p>
            <?php
            if(isset($_SESSION['e_haslo']))
            {
                echo '<div class = "error">'.$_SESSION['e_haslo'].'</div>';
                unset($_SESSION['e_haslo']);
            }
            ?>
            <p>Powtórz hasło: <br>
                <input type="password" name="haslo2"/></p>
                        <div class="g-recaptcha" data-sitekey="6LdJih0TAAAAADElB7FgyU4CsgD3UDZhq_wMiN2X"></div>
            <?php
            if(isset($_SESSION['e_bot']))
            {
                echo '<div class = "error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            }
            ?>
            <p><input type="submit" name="send" value="Rejestracja"></p>
        </form>
                <p><a href="../index.php">Powrót na strone główną</a></p>
                </div>
            </div>
         </div>
                      </center>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </body>
</html>
