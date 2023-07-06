<?php

    // Sign the user out by deleting the cookies and then redirecting to sign_in.php
    setcookie("username", "", time() - (14 * 24 * 60 * 60), "/", "", true, false);
    setcookie("password", "", time() - (14 * 24 * 60 * 60), "/", "", true, false);
    header("Location: ../sign_in.php");

?>