<?php

    // Get the username to display in the drawer
    if (!function_exists('getLoginInfo')){
        // Import if not already imported
        include "widgets/login_functions.php";
    }
    $loginInfo = getLoginInfo();

?>

<!-- This code is for the drawer. It is sourced from https://getbootstrap.com/docs/5.0/components/offcanvas/ -->
<!-- You can import this file and it will load this code. Keep in mind you will still need to set a trigger to open -->
<!-- it. See the image in the header of home_screen.php for reference. The <a> tag is what triggers the drawer to open -->
<!-- This code was exposed to chatGPT on 2/9/23 Jan 30 version -->
<div class="offcanvas offcanvas-end drawer" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
    <img src="https://via.placeholder.com/50x50" class="rounded-circle header-logo" alt="User Profile Picture">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo htmlspecialchars($loginInfo['username']) ?></h5>
    <button type="button" class="drawer-exit-button" action="<?php echo "{$_SERVER['PHP_SELF']}"?>?signout=true" data-bs-dismiss="offcanvas" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
        </svg>
    </button>
    </div>
    
    <div class="offcanvas-body">
    <div class="drawer-spacer"></div>
    <a class="drawer-button" id="sign-out-btn"> Sign Out </a>
    <a class="drawer-button" id="contact-us-btn"> Contact us </a>
    </div>

    <script src="../scripts/widget_scripts/drawer-functionality.js"></script>

    <!-- Make sure bootstrap is loaded -->
    <!-- DONT ACTIVATE: it causes the drawer to not close properly when you click away<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css"> <!-- Reload style.css so that it takes priority over bootstrap css -->
</div>
