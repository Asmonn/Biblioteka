<?php 
session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: ../index.php');
    exit();
}

require_once '../database/config.php';

$login1 = $_SESSION['login'];
//$data1 = NOW();
//$rezer = $_POST['rez'];

$connect = @new mysqli($host, $dbuser, $dbpass, $dbname);
if ($connect->connect_errno != 0) 
{
    echo "Error: ".$connect->connect_errno;
}
 else 
{
    $result1 = @$connect->query("select idbook, tytul, autor, stan from books");

             
    //$connect->close();
}

if(isset($_POST['rez']))
{
    
    $rezer = $_POST['rez'];
    
    $connect = @new mysqli ($host, $dbuser, $dbpass, $dbname);
    if($connect->connect_errno!=0)
    {
        echo "Error: ".$connect->connect_errno;
    }
    else
    {
        $result2 = @$connect->query("select stan from books where idbook='$rezer'");
            
        $roww2 = $result2->num_rows;
        
        if($roww2 > 0)
        {            
            $roww3 = $result2->fetch_row();
            if($roww3[0] == "wolny")
            {
                $result3 = @$connect->query("select * from historia where login='$login1' and stanz is NULL");
                $roww4 = $result3->num_rows;
                if($roww4 > 0)
                {
                    $_SESSION['e_zaj'] = "Prosze oddać inną książkę, która nie została jeszcze oddana do bilbioteki";
                }
                else
                {
                    if($connect->query("INSERT INTO historia (login, idbook, stanr) VALUES ('$login1', '$rezer', NOW())"))
                    {
                        $connect->query("update books set stan='zaj' where idbook='$rezer'");
                        $_SESSION['udanarez'] = "Udało sie zarezerwować książkę proszę w przeciągu 7 dni odebrać ją osobiście w bilbiotece";
                    }
                    else
                    {
                        echo "Error: ".$connect->connect_errno;
                    }
                }
            }
            else
            {
                $_SESSION['e_wyp'] = "Ta książka jest wypożyczona";
            }
        }
        else
        {
            $_SESSION['e_brak'] = "Przepraszamy ale nie mamy takiej książki w magazynie";
        }
                      
        $connect->close();
    }   

}
else
{
    $_SESSION['e_id'] = "Proszę podać ID";
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
        <table border="1"><tr>
                <td>ID książki</td>
                <td>Autor</td>
                <td>Tytuł</td>
                <td>Stan</td>
            </tr>
            <?php
            while($roww = $result1->fetch_row())
            {
                echo "</tr>";
                echo "<td>". $roww[0] ."</td>";
                echo "<td>". $roww[2] ."</td>";
                echo "<td>". $roww[1] ."</td>";
                echo "<td>". $roww[3] ."</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <p>Proszę podać ID książki a potem kliknąć "Rezerwuj"</p>
        <form method="post">
        <p><input type="text" name="rez"/></p>
        <?php
        if(isset($_SESSION['udanarez']))
        {
            echo '<div class = "error">'.$_SESSION['udanarez'].'</div>';
            unset($_SESSION['udanarez']);
        }
        
        if(isset($_SESSION['e_wyp']))
        {
            echo '<div class = "error">'.$_SESSION['e_wyp'].'</div>';
            unset($_SESSION['e_wyp']);
        }
        
        if(isset($_SESSION['e_brak']))
        {
            echo '<div class = "error">'.$_SESSION['e_brak'].'</div>';
            unset($_SESSION['e_brak']);
        }
        
        if(isset($_SESSION['e_id']))
        {
            echo '<div class = "error">'.$_SESSION['e_id'].'</div>';
            unset($_SESSION['e_id']);
        }
        
        if(isset($_SESSION['e_zaj']))
        {
            echo '<div class = "error">'.$_SESSION['e_zaj'].'</div>';
            unset($_SESSION['e_zaj']);
        }
        ?>
        <p><input type="submit" name="send" value="Rezerwuj"></p>
        </form>
        <p><a href="user.php">Powrót na stronę główną</a></p>
    </center>
</body>
</html>
