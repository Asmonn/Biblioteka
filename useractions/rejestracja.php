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
                if($connect->query("insert into userdata values ('$login', 'xxxx', 'yyyy', '$email', '$haslo_hash')"))
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
        <title>Biblioteka online</title>
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
            <!--<p><label>
                    <input type="checkbox" name="regulamin" /> Akceptuję regulamin
                </label></p>-->
            <p><input type="submit" name="send" value="Rejestracja"></p>
        </form>
    </center>
    </body>
</html>
