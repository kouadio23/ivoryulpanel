<?php

    if (isset($_POST['whereto'])){
        echo $_POST['whereto'];

        if ($_POST['whereto'] == "Go to Legacy"){
            header('Location: legacy_codebase/panel_form.php');
        } else if ($_POST['whereto'] == "Go to Home screen"){
            header('Location: pages/home_screen.php');
        } else if ($_POST['whereto'] == "Go to Login screen"){
            header('Location: pages/sign_in.php');
        } else if ($_POST['whereto'] == "Go to Sign Up screen"){
            header('Location: pages/account_creation.php');
        }
    
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST"> 
        <input type="submit" name="whereto" value="Go to Legacy">
        <input type="submit" name="whereto" value="Go to Home screen">
        <input type="submit" name="whereto" value="Go to Login screen">
        <input type="submit" name="whereto" value="Go to Sign Up screen">
    </form>
</body>
</html>