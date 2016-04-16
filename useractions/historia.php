<?php 
session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: ../index.php');
    exit();
}

require_once '../database/config.php';

$login2 = $_SESSION['login'];

$connect = @new mysqli($host, $dbuser, $dbpass, $dbname);
if ($connect->connect_errno != 0) 
{
    echo "Error: ".$connect->connect_errno;
}
 else 
{
    $result1 = @$connect->query("select b.autor, b.tytul, h.stanr from historia h, books b where b.idbook = h.idbook and h.login='$login2'");

    
    //$connect->close();
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
        <table border="1"><tr>                
                <td>Autor</td>
                <td>Tytuł</td>
                <td>Data rezerwacji online</td>
            </tr>
            <?php
            while($roww = $result1->fetch_row())
            {
                echo "</tr>";
                echo "<td>". $roww[0] ."</td>";
                echo "<td>". $roww[1] ."</td>";
                echo "<td>". $roww[2] ."</td>";                
                echo "</tr>";
            }
            ?>
        </table>
        <p><a href="user.php">Powrót na stronę główną</a></p>
    </center>
    </body>
</html>
