<?php

    session_start();

    if (array_key_exists("ID", $_COOKIE)) {
        
        $_SESSION['ID'] = $_COOKIE['ID'];
        
    }

    if (array_key_exists("ID", $_SESSION)) {
        
        echo "<p>Logged In! <a href='index.php?logout=1'>Log out</a></p>";
        
    } else {
        
        header("Location: index.php");
        
    }

include("header.php");
?>
<div class="container-fluid">

<textarea id="diary" class="form-control"></textarea>

</div>


<?php include("footer.php");?>