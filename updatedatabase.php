<?php

    session_start();

    if (array_key_exists("content", $_POST)) {
        
        include("connection.php");
        
        $query = "UPDATE `login` SET `diary` = '".mysqli_real_escape_string($link, $_POST['content'])."' WHERE ID = ".mysqli_real_escape_string($link, $_SESSION['ID'])." LIMIT 1";
        
        mysqli_query($link, $query);
        
    }

?>

