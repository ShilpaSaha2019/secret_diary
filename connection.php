<?php

    $link = mysqli_connect("localhost", "root", "root", "users");
        
        if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }

?>