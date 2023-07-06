<?php

   

    //Check if the user is logged in. If they aren't send them to sign_in.php
    include "widgets/login_functions.php";
    if (!checkLogin()){
      header("Location: sign_in.php");
    }


    include 'dummy_data/temp_dummy_projects.php';

    if (isset($_GET['goHome']) && isset($_GET['goHome']) == "true"){
        header("Location: home_screen.php");
    }

    // Echo to the screen if the project ID is invalid
    if (!isset($_GET['projectID'])){
        echo "ERROR: Invalid project ID!";
    }

    // Get this project object
    $thisProject = $projects[$_GET['projectID']];

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
    <title><?php echo "{$thisProject['id']}"?></title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
</head>
<body class="workstation-body">

    <header>
        <!-- This nav bar was created by ChatGPT (Jan 9 version) on 1/19/23. It has been modified significantly -->
        <nav class="navbar header">
            
          <a href="<?php echo $_SERVER['PHP_SELF'];?>?goHome=true" class="navbar-brand header-button header-button-bigger">Home</a>

          <a class="btn nav-link" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">
              <img src="https://via.placeholder.com/50x50" class="rounded-circle header-logo" alt="User Profile Picture">
          </a>

        </nav>
    </header>    

    <!-- Imports the code for the drawer body code. This is triggered via clicking on the pfp image -->
    <?php include "./widgets/user-account-drawer.php"?>

    <canvas class="test-canvas"></canvas>

    <script src="../scripts/workstation.js" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <p class="fps-counter">FPS: 0</p>
              
</body>
</html>