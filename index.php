<?php
    
    session_start();

    $error = "";    

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        setcookie("ID", "", time() - 60*60);
        $_COOKIE["ID"] = "";  
        
    } else if ((array_key_exists("ID", $_SESSION) AND $_SESSION['ID']) OR (array_key_exists("ID", $_COOKIE) AND $_COOKIE['ID'])) {
        
        header("Location: loggedinpage.php");
        
    }
    
    if(array_key_exists("submit",$_POST))
    {

        $link=mysqli_connect("localhost","root","root","users");

        if(mysqli_connect_error())
        {
            die ("Database connection failed!!");
        }

        if(!$_POST['email'])
        {
            $error.="An email address is required";
        }

        if(!$_POST['password1'])
        {
            $error.="A password is required";
        }

        if($error!="")
        {
            $error="<p>There is an error in your form<p>".$error;
        }

        else
        {
            if ($_POST['signUp'] == '1') 
            {
            $query="SELECT ID FROM `login` WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1";//selects ID with email address same as email address that has been input
        
            $result = mysqli_query($link, $query);//run query
        
            if (mysqli_num_rows($result) > 0)//checks the row exists or not
            {
                $error="That Email address has already been taken!";
            }
            else{
            // $query="INSERT INTO `login`(`email`,`pass`) VALUES ('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password1'])."')";
            $query="INSERT INTO `login`(`email`,`password`) VALUES ('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password1'])."')";

            if (!mysqli_query($link, $query)) {

                $error = "<p>Could not sign you up - please try again later.</p>";

            } 
            else 
            {
                $idFetched=mysqli_insert_id($link);
                $query = "UPDATE `login` SET password = '".md5(md5($idFetched).$_POST['password1'])."' WHERE ID = ".$idFetched." LIMIT 1";
                
                        mysqli_query($link, $query);

                        $_SESSION['ID'] = mysqli_insert_id($link);

                        if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("ID", mysqli_insert_id($link), time() + 60*60*24*365);

                        } 
                        header("Location: loggedinpage.php");
            }
        
           }   
        }
        else {
                    
            $query = "SELECT * FROM `login` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
        
            $result = mysqli_query($link, $query);
        
            $row = mysqli_fetch_array($result);
        
            if (isset($row)) {
                
                $hashedPassword = md5(md5($row['ID']).$_POST['password1']);
                
                if ($hashedPassword == $row['password']) {
                    
                    $_SESSION['ID'] = $row['ID'];
                    
                    if ($_POST['stayLoggedIn'] == '1') {

                        setcookie("ID", $row['ID'], time() + 60*60*24*365);

                    } 

                    header("Location: loggedinpage.php");
                        
                } else {
                    
                    $error = "That email/password combination could not be found.";
                    
                }
                
            } else {
                
                $error = "That email/password combination could not be found.";
                
            }
        }
    }

}
?>




<?php include("header.php"); ?>
<body>

<div class="container" id="homePageContainer">
    
<h1>My Secret Diary</h1>
<p><strong>Store your thoughts permanently and securely.</strong></p>

<div id="error"><?php echo $error; ?></div>

<form method="POST" id="signUpForm">
<p>Interested? Sign Up</p>
<div class="form-group">
  <input type="email" class="form-control" name="email" placeholder="Your Email ID">
  </div>
  <div class="form-group">
  <input type="password" class="form-control"name="password1" placeholder="Your Password">
  </div>
  <div class="form-group">
  <input type="checkbox" name="stayLoggedIn" value=1>
  Stay Logged In
  </div>
  <div class="form-group">  
  <input type="hidden" name="signUp" value="1">
  <input type="submit" class="btn btn-success" name="submit" value="Sign Up!">
  </div>

  <p><a class="toggleForms">Log In</a></p>
   
</form>

<form method="POST" id="logInForm">
  <p>Log In</p>
  <div class="form-group">
  <input type="email" class="form-control"  name="email" placeholder="Your Email ID">
  </div>
  <div class="form-group">
  <input type="password" class="form-control" name="password1" placeholder="Your Password">
  </div>
  <div class="form-group">
  <input type="checkbox"  name="stayLoggedIn" value=1> 
  <input type="hidden" name="signUp" value="0">
  Stay Logged In
  </div>
  <div class="form-group"> 
  <input type="submit" class="btn btn-success" name="submit" value="Log In!">
  </div>

  <p><a class="toggleForms">Sign Up</a></p>
</form>

</div>

<?php include("footer.php");?>
</body>
</html>
