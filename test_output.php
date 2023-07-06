<?php
session_start();
if (isset($_POST["Test_Number"])) {
    //echo "Test Number: ";
    $test_number = $_POST["Test_Number"];
    //echo $_POST["Test_Number"];

    if ($test_number >= 10) {
        // ERROR!
        $newURL = "test_form.php";
        $_SESSION['error'] = 1;
        $_SESSION['test_number'] = $test_number;
        header('Location: '.$newURL);
    } else {
        // SUCCESS!
        echo "$test_number is less than 10!";
        $_SESSION['error'] = 0;
    }

} else {
    echo "No Test Number";
}

?>