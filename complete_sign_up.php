<?php

    include "widgets/login_functions.php";

    session_start();

    $error_map = [];

    // If they aren't set send the user back to account_creation
    if (!(isset($_SESSION['username']) || !isset($_SESSION['email']))){
      header("Location: account_creation.php");
    }

    // Check if this is a redirect from our site. 
    if (isset($_SESSION['from_us']) && $_SESSION['from_us'] == "true"){
      $from_us = true;
    } else {
      $from_us = false;
    }
    
    // Check if this was a submit with user data
    if (isset($_POST['submit'])){
      // We know that the user at least tried to create it. 


      // Check if everything is here & valid. If not, show error
      $validData = true;

      // Check that the username isn't taken
      if (isset($_POST['username']) && keyIsTaken("users", "username", $_POST['username'])){
        $error_map['username'] = "Username is NOT available. Please try something else";
        $validData = false;
      } 
      

      // Checks all the inputs to make sure they aren't empty
      if ((!isset($_POST['username']) || strlen($_POST['username']) < 1)){
        $error_map['username'] = "Username is required";
        // $validData = false;
      }
      if (!isset($_POST['phoneNumber']) || strlen($_POST['phoneNumber']) < 1){
        $error_map['phoneNumber'] = "Phone Number is required";
        $validData = false;
      }
      if (!isset($_POST['password']) || strlen($_POST['password']) < 1){
        $error_map['password'] = "Password is required";
        $validData = false;
      }

      
      if (!isset($_POST['verifyPassword']) || strlen($_POST['verifyPassword'] < 1)){
        $error_map['verifyPassword'] = "Password verification is required";
        $validData = false;
      } else if (!($_POST['verifyPassword'] == $_POST['password'])){
        // Passwords must also match
        $error_map['verifyPassword'] = "Passwords must match";
        $validData = false;
      }      

      
      if ($validData){
        // only runs if everything is here


        try {
          $connection = @mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');

          
          // organizes the data
          if (isset($_POST['username'])){
            $username = mysqli_real_escape_string($connection, $_POST['username']);
          } else {
            $username = mysqli_real_escape_string($connection, $_SESSION['username']);
          }
          $email = mysqli_real_escape_string($connection, $_SESSION['email']);
          $passwordHash =  mysqli_real_escape_string($connection, password_hash($_POST['password'], PASSWORD_ARGON2ID));
          $phoneNumber = mysqli_real_escape_string($connection, $_POST['phoneNumber']);

          // Write the query and execute it
          $sql_query = "INSERT INTO `users` (username, email, password, phone_number) VALUES ('$username', '$email', '$passwordHash', '$phoneNumber')";
          $result = mysqli_query($connection, $sql_query);

          if ($result){
            if (mysqli_affected_rows($connection) == 1){

              // Inserted sucessfully. Create cookies (expire after 2 weeks) & Redirect to home
              setcookie("username", htmlspecialchars($username), time() + (14 * 24 * 60 * 60), "/", "", true, false); // This line was exposed to chatGPT on 2/9/23
              setcookie("password", htmlspecialchars($_POST['password']), time() + (14 * 24 * 60 * 60), "/", "", true, false);

              header('Location: home_screen.php');

            } else {
              $error_map['sql'] = "Something went wrong and we couldn't create your account." .
                      "If this issue persists, please contact us <a href='contact_us.php'> here </a>.";
            }
          }

          mysqli_free_result($result);

        } catch (Exception $e) {
          // Catch the sql error and show the user
          $error_map['sql'] = "Something went wrong and we couldn't create your account. " .
                      "If this issue persists, please contact us <a href='contact_us.php'> here </a>.";
        }        

      }

    }


    /*
      Determines what values to put in each box
    */
    if (isset($_POST['username'])){
      $username_value = "value='{$_POST['username']}'";
    } else if (isset($_SESSION['username'])) {
      $username_value = "value='{$_SESSION['username']}'";
    } else {
      $username_value = "";
    }

    if (isset($_POST['phoneNumber'])){
      $phoneNumber_value = "value='{$_POST['phoneNumber']}'";
    } else {
      $phoneNumber_value = "";
    }

    if (isset($_POST['password'])){
      $password_value = "value='{$_POST['password']}'";
    } else {
      $password_value = "";
    }

?>

<html>
    <head>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../styles/style.css">
    </head>
    <body class="complete_sign_up-body">

        <nav class="navbar navbar-default header">
          <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand header-brand" href="sign_in.php">Control Panel Builder App</a>
            </div>
          </div>
        </nav>

        <?php
          // Show SQL errors if they occur
          if (isset($error_map['sql'])){
            echo "<p class='complete-registration-sql-error'>" . $error_map['sql'] . "</p>";
          }
        ?>

        <!-- Show the inputs -->
        <div class="container complete-registration-div">
            <h2 class="text-center complete-registration-title">Complete registration</h2>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

                <!-- Only prompt for a username if its not from_us, meaning redirect from account_creation.php -->
                <?php if (!$from_us) { ?>

                  <div class="form-group">
                      <label for="username" class="complete-registration-label">Username:</label>
                      <input type="text" name="username" class="form-control complete-registration-input <?php echo (isset($error_map['username'])) ? "complete-registration-error" : ""; ?>" id="username" placeholder="Enter Username" <?php echo $username_value?>>
                      <p class="complete-registration-error-message"> <?php echo (isset($error_map['username'])) ? htmlspecialchars($error_map['username']) : ""; ?> </p>
                  </div>

                <?php }?>

                <div class="form-group">
                    <label for="phoneNumber" class="complete-registration-label">Phone Number:</label>
                    <input type="text" name="phoneNumber" class="form-control complete-registration-input <?php echo (isset($error_map['phoneNumber'])) ? "complete-registration-error" : ""; ?>" id="phoneNumber" placeholder="Enter Phone Number" <?php echo $phoneNumber_value?>>
                    <p class="complete-registration-error-message"> <?php echo (isset($error_map['phoneNumber'])) ? htmlspecialchars($error_map['phoneNumber']) : ""; ?> </p>
                </div>
                
                <div class="form-group">
                    <label for="password" class="complete-registration-label">Password:</label>
                    <input type="password" name="password" class="form-control complete-registration-input <?php echo (isset($error_map['password'])) ? "complete-registration-error" : ""; ?>" id="password" placeholder="Enter password" <?php echo $password_value?>>
                    <p class="complete-registration-error-message"> <?php echo (isset($error_map['password'])) ? htmlspecialchars($error_map['password']) : ""; ?> </p>
                </div>

                <div class="form-group">
                    <label for="verifyPassword" class="complete-registration-label">Verify Password:</label>
                    <input type="password" name="verifyPassword" class="form-control complete-registration-input <?php echo (isset($error_map['verifyPassword'])) ? "complete-registration-error" : ""; ?>" id="verifyPassword" placeholder="Verify password">
                    <p class="complete-registration-error-message">  <?php echo (isset($error_map['verifyPassword'])) ? htmlspecialchars($error_map['verifyPassword']) : ""; ?> </p>
                </div>

                <div class="complete-registration-submit-container">
                  <button type="submit" name="submit" class="complete-registration-submit">Submit</button>
                </div>
            </form>
        </div>
    </body>
</html>