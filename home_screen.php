<?php

    
    //Check if the user is logged in. If they aren't send them to sign_in.php rgrgr
    include "widgets/login_functions.php";
    if (!checkLogin()){
      header("Location: sign_in.php");
    }


  


    // Import dummy data
    include 'dummy_data/temp_dummy_projects.php';
    

    // If there is get information then act on it
    if (isset($_GET['newProject']) && $_GET['newProject'] == "true"){
      echo "<script> alert('Adding a new project is coming soon!');</script>";
    } else if (isset($_GET['openProject'])){
      header("Location: workstation.php?projectID={$_GET['openProject']}");
    } else if (isset($_POST['createProject'])){
      // TODO: create a project
      echo "<i>COMING SOON: Project creation</i>";
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
</head>

<body class="home-screen-body">

    <header>
        <!-- This nav bar was created by ChatGPT (Jan 9 version) on 1/19/23. It has been modified significantly -->
        <nav class="navbar header">           
      
          <!-- Exposed to chatgpt jan 9 version on 1/20/23 -->
          <p class="header-button header-button-bigger" id="header-new-panel-btn">New Panel</p>

          <a class="btn nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
              <img src="https://via.placeholder.com/50x50" class="rounded-circle header-logo" alt="User Profile Picture">
          </a>

        </nav>
    </header>

    <!-- Imports the code for the drawer body code. This is triggered via clicking on the pfp image -->
    <?php include "./widgets/user-account-drawer.php"?>


    <section id="home-screen-project-grid">

      <?php 

        foreach ($projects as $project) {
          echo "<a href='{$_SERVER['PHP_SELF']}?openProject={$project['id']}'>";
          echo "<div class=\"project-square\">";
          echo "<img src='{$project['screenshot']}' alt='Project screenshot image' class='project-screenshot'>";
          echo "<p> {$project['project_name']} </p>";
          echo "<p> {$project['project_creator']} </p>";
          echo "</div>";
          echo "</a>";
        }

      ?>

      <!-- "Add" button -->
      <!-- Exposed to ChatGPT jan 9 version on 1/19/2023 -->
      <div id="home-screen-add-button" class="project-square" onclick="new_panel_open()" style="cursor: pointer">
        <p id="home-screen-add-icon"> + </p>
      </div>
        
    </section>


    <div class="formPopup" id="new_panel_popup">
      <form action='<?php echo "{$_SERVER['PHP_SELF']}"?>' class="formContainer" method='POST'>
        <label for="projectName">
          <strong>Project Name</strong>
        </label>
        <input type="hidden" name="createProject" value="true">
        <input type="text" id="projectName" placeholder="New Project" name="projectName" required>
        <!-- <label for="psw">
          <strong></strong>
        </label>
        <input type="password" id="psw" placeholder="Your Password" name="psw" required> -->
        <button type="submit" class="btn">Create project</button>
        <button type="button" class="btn cancel" onclick="new_panel_close()">Cancel</button>
      </form>
    </div>

    <script src="../scripts/home_screen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
</body>
</html>