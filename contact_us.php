<?php

    include "./widgets/login_functions.php";
    
    if (checkLogin()) {
        // $email_value = $_COOKIE['email'];

        $login = getLoginInfo();
        $email_value = $login['email'];
        unset($login);


    } else if (isset($_SESSION['email'])) {
        $email_value = $_SESSION['email'];
    } else {
        $email_value = "";
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../styles/style.css">
    <title>Control Panel Builder App - Contact Us</title>
</head>
<body class="contact-us-body">
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


    <div class="container contact-us-div">
        <p class="contact-us-title">Contact Us</p>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" value="<?php echo $email_value ?>" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" rows="5" id="message" name="message" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-default" onclick="submit()">Submit</button>
        </form>
    </div>

    <?php
    
        if (isset($_POST['submit'])){
            echo "<script> alert('Contact us is coming soon!'); </script>";
        }

    ?>
</body>
</html>