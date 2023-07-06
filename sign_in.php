<?php
// Initialize the session
session_start();


//Check if the user is logged in. If yes then redirect them to home page
include "widgets/login_functions.php";

if (checkLogin()){
  header("Location: home_screen.php");
}



$link = mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // // Password is correct, so start a new session
                            // session_start();
                            
                            // // Store data in cookies
                            // setcookie("id",$id,time()+60*60*24*30);
                            // setcookie("username", $username, time() + 60 * 60 * 24 * 30);
                            // setcookie("password", $password, time() + 60 * 60 * 24 * 30);



                            // Password is correct, generate and store a new token for the user
                             $token = bin2hex(random_bytes(32));
                            $expires = time() + 3600; // Token will expire in 1 hour
                            // $sql = "INSERT INTO tokens (user_id, token, expires) VALUES (?, ?, ?)";
                            //$sql = "INSERT INTO users (id, token, token_expire_length) VALUES (?, ?, ?)";
                            
                            $sql = "UPDATE users SET token = ?, token_expire_length = ? WHERE id = ?";
                            if($stmt = mysqli_prepare($link, $sql)){
                            mysqli_stmt_bind_param($stmt, "iss", $id, $token, $expires);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                            }
                   
                           // Set the token as a cookie
                           setcookie("token", $token, $expires, "/", "ulpanelapp.com", true, true);

                                     
                            // Redirect user to welcome page
                            header("location: home_screen.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel='stylesheet' href = '../styles/style.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Ibex Sign in</title>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>
		function onSignIn(googleUser) {
			// Get the Google user ID token
			var id_token = googleUser.getAuthResponse().id_token;
			
			// Send the token to the server for verification
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'https://example.com/login.php');
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onload = function() {
				if (xhr.status === 200) {
					// Redirect to the protected page
					window.location.href = 'https://example.com/protected.php';
				} else {
					console.error('Error: ' + xhr.status);
				}
			};
			xhr.send('idtoken=' + id_token);
		}
	</script>
</head>
<body class="sign-in-body">

    <nav class="navbar navbar-default header">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand header-brand" href="sign_in.php">Control Panel Builder App</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="about_us.php" class="header-button">About Us</a></li>
            <li><a href="contact_us.php" class="header-button">Contact Us</a></li>
            <li><a href="account_creation.php" class="header-button">Sign up</a></li>
        </ul>
    </div>
   </nav>

    <div class="grid-c">
        <img class="logo" src="https://via.placeholder.com/100/09f.png/000000">
        <h2 class="welcome">Welcome</h2>

        

        <form class = "sign-in-left sign-in-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>
            <input class='si-input <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"' type ="text" placeholder="Username" name = 'username' required>
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
            <input class='si-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">' type="password" placeholder = "Password"name = 'password' required>
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
            <button class = "sign-in" type="submit">Sign in</button>
            <p class='sip'>Don't have an account? <a href="account_creation.php">Sign up</a></p>
        </form>
        <div class="sign-in-right">
            <form class='sign-in-form'>
                <button class="ssosignin" data-onsuccess ="onSignIn">
                  Sign in with Google
                </button>
            </form>
            <form class='sign-in-form'>
                <button class="ssosignin" type ="submit">
                  Sign in with Microsoft
                </button>
            </form>
            <form class='sign-in-form'>
                <button class="ssosignin" type = "submit">
                  Sign in with Facebook
                </button>
            </form>
        </div>
    </div>
    <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->
  <script>

    //Frontend token script
    document.getElementById('login-form').addEventListener('submit', function(event) {
      event.preventDefault();

      const form = event.target;
      const formData = new FormData(form);

      fetch('/login_functions.php', {
        method: 'POST',
        body: formData
      }).then(response => {
        if (response.ok) {
          response.text().then(token => {
            // store the token in local storage or a cookie
            localStorage.setItem('token', token);
            // redirect to the user's dashboard or the next page
            window.location.href = '/home_screen.php';
          });
        } else {
          response.text().then(message => {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.style.color = 'red';
          });
        }
      });
    });
  </script> 
</body>

</html>