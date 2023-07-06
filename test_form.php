
<?php
session_start();

echo "Redirect Test<br>";

if (isset($_SESSION['error']) && ($_SESSION['error'] == 1)) {
    echo "<br> ERROR! Number entered must be less than 10.<br>";
}

if (isset($_SESSION['test_number'])) {
    $test_number = $_SESSION['test_number'];

    echo "<br> Wrong Number entered was: $test_number <br>";
} else {
    echo "<br> No test number<br>";
    $test_number = "";
}
?>


<form method="post" action="test_output.php" id="test_form">
    Test Number:
    <input name="Test_Number" id="Test_Number" <?php echo 'value="'.($test_number).'"'?>>
    <button type="submit">Submit</button>
</form>

