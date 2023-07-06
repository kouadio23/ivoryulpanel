<?php

    include "widgets/login_functions.php";

    $error_map = [];


    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        // Checks that username and email is set
        if (isset($_POST['username']) && isset($_POST['email'])){

            // Check that they are available
            $validData = true;

            if (keyIsTaken('users', 'username', $_POST['username'])){
                $error_map['username'] = "Username isn't available. Please try something else";
                $validData = false;
            }

            if (keyIsTaken('users', 'email', $_POST['email'])) {
                $error_map['email'] = "Email isn't available. Please try something else";
                $validData = false;
            }

            if ($validData){
                // Set session variables then redirect to complete_sign_up.php
                session_start();

                $_SESSION['username'] = $_POST['username'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['from_us'] = "true";

                header("Location: complete_sign_up.php");
            }

        } 

    } 
    

?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <link rel="stylesheet" href="../styles/style.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Create Account</title>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src="https://apis.google.com/js/platform.js" async defer></script>

  <script>
      function submitForm() {
        window.location.href = "complete_sign.php";
      }
    </script>

</head>
<body class="account-creation-body">

  <nav class="navbar navbar-default header">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand header-brand" href="sign_in.php">Control Panel Builder App</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="about_us.php" class="header-button">About Us</a></li>
            <li><a href="contact_us.php" class="header-button">Contact Us</a></li>
            <li><a href="sign_in.php" class="header-button">Login</a></li>
        </ul>
    </div>

</nav>
 <div class="grid-c-account-creation">

    <img class="logo" src="https://via.placeholder.com/100/09f.png/000000"> 
    <h2 class="welcome-account-creation">ACCOUNT</h2>

      <form class="sign-up-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">   
        <input type ="text" class= "form-input" name="username" placeholder="Username" required>
        <p class="complete-registration-error-message"> <?php echo (isset($error_map['username'])) ? htmlspecialchars($error_map['username']) : ""; ?> </p>
        <input type ="email" class="form-input"  name="email" placeholder="Email" required>
        <p class="complete-registration-error-message"> <?php echo (isset($error_map['email'])) ? htmlspecialchars($error_map['email']) : ""; ?> </p>
        <label for="terms">I agree to the <a href="#">terms and conditions</a>:</label>
        <input type="checkbox" id="terms" name="terms" required><br>
        <div class="g-recaptcha" data-sitekey="6LcqfbskAAAAAIjdG2xQQTQ-i1B2NLzmXYBqzp26"></div>
        <input type="submit" class= "form-input" value="Submit" onclick="submitForm()">
      </form>
   <div class="grid-right-account-creation">
  <form>
    <h>Or sign up with:</h>
  </form>
  <form >
      <button class="ssosignup" type ="submit">

          Sign in with Google
      </button>
  </form>
  <form>
      <button class="ssosignup" type ="submit">
          Sign in with Microsoft
      </button>
  </form>
  <form >
      <button class="ssosignup" type = "submit">
          Sign in with Facebook
      </button>
  </form>
  
  </div>
</div>
</body>

</html>

