<?php
    session_start();
?>
<html>
<head>
    <title>UL Panel App</title>
    <script type="text/javascript" src="form.js"></script>    
    <script> console.log("succesfully updated"); </script>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
        //Checks for session error variables and displays at top of screen
        if (!empty($_SESSION["errors"])) {
            $numErrors = count($_SESSION["errors"]);
            $i = 0;
            foreach ($_SESSION["errors"] as $error) {
                echo ("<font color=red>" . $error);
                if(++$i != $numErrors) {
                    echo "<br>";
                }
            }
            echo ("<font color=white>");
        }
    ?>

    <div class="input_panel">
        <!-- Form 2: Input name, hp, starter type, and disconnect type for each motor. -->
        <form method="post" action="generate_panel.php" id="form2">
            <!-- HP typical sizes according to Table 50.1 of UL 508A. -->
            <?php
                unset($_SESSION['errors']);

                //Sets error session variables for each drop down box in panel_form.php and sends 
                //variables back to panel_form.php
                if (isset($_POST["motors"])) {
                    $motors=$_POST['motors'];
                    $_SESSION['motors'] = $_POST["motors"];
                    $_SESSION['voltage'] = $_POST["voltage"];
                    $_SESSION['phase'] = $_POST["phase"];
                    $_SESSION['temp'] = $_POST["temp"];

                    $motorserr = $_SESSION['motors'] == "~Amount of Motors~";
                    $voltageerr = $_SESSION['voltage'] == "~Voltage~";
                    $phaseerr = $_SESSION['phase'] == "~Phase~";
                    $temperr = $_SESSION['temp'] == "~Ambient Temperature~";
                    if ($motorserr) {
                        $_SESSION['errors']['form1'] = "Error: Missing field<br>";
                        $_SESSION["motorserr"] = true;
                    }
                    if ($voltageerr) {
                        $_SESSION['errors']['form1'] = "Error: Missing field<br>";
                        $_SESSION["voltageerr"] = true;
                    }
                    if ($phaseerr) {
                        $_SESSION['errors']['form1'] = "Error: Missing field<br>";
                        $_SESSION["phaseerr"] = true;
                    }
                    if ($temperr) {
                        $_SESSION['errors']['form1'] = "Error: Missing field<br>";
                        $_SESSION["temperr"] = true;
                    }
                    
                } else {
                    $motors=$_SESSION['motors'];
                }

                if (!empty($_SESSION['errors'])) {
                    $newURL = "panel_form.php";
                    echo "<script type='text/javascript'>document.location.href='{$newURL}';</script>";
                }

                echo "<h1 class='page_title'>UL Panel Generator</h1>";
                echo "<h2 class='input_title'>Input motor names and specs of each motor:</h2>";
                
                //For loop that displays all options for amount of motors selected in panel_form.php
                for ($i = 1; $i <= intval("$motors"); $i++){
                    echo "<input class='cool_drpdwn' name='motor_$i' value='motor_$i'>";

                    //Sets border for dropdown box red if missing horsepower selection
                    $selected = "";
                    if (isset($_SESSION['motor_form']['horsepower'.$i])) {
                        $selected = array_key_first($_SESSION['motor_form']['horsepower'.$i]);
                        if (array_values($_SESSION['motor_form']['horsepower'.$i])[0]) {
                            echo "<select class='cool_drpdwn' style='border-color:red' name='horsepower$i' id='horsepower$i'>";
                        } else {
                            echo "<select class='cool_drpdwn' name='horsepower$i' id='horsepower$i'>";
                        }
                    } else {
                        echo "<select class='cool_drpdwn' name='horsepower$i' id='horsepower$i'>";
                    }
                    //References xml file for horsepower options for each motor and displays in a dropdown box
                    $xml = simplexml_load_file("reference.xml");
                    $hp = $xml->xpath("/tables/horsepower/hp");
                    echo "<option>~Horsepower~</option>";
                    foreach ($hp as $hps) {
                        foreach($hps->attributes() as $type) {
                            if ($type == $selected) {
                                echo "<option selected value='$type'>$type</option>";
                            } else {
                                echo "<option value='$type'>$type</option>";
                            }
                        }
                    }
                    echo "</select>";
                    unset($_SESSION['motor_form']['horsepower'.$i]);
                    
                    //Sets border of dropdown box red if starter has no selection
                    $selected = "";
                    if (isset($_SESSION['motor_form']['starter'.$i])) {
                        $selected = array_key_first($_SESSION['motor_form']['starter'.$i]);
                        if (array_values($_SESSION['motor_form']['starter'.$i])[0]) {
                            echo "<select class='cool_drpdwn' style='border-color:red' name='starter$i' id='starter$i'>";
                        } else {
                            echo "<select class='cool_drpdwn' name='starter$i' id='starter$i'>";
                        }
                    } else {
                        echo "<select class='cool_drpdwn' name='starter$i' id='starter$i'>";
                    }
                    //References xml file for starter types and displays in dropdown box for each motor
                    $xml = simplexml_load_file("reference.xml");
                    $starter = $xml->xpath("/tables/starters/start");
                    echo "<option>~Starter Type~</option>";
                    foreach ($starter as $start) {
                        foreach($start->attributes() as $type) {
                            if ($type == $selected) {
                                echo "<option selected value='$type'>$type</option>";
                            } else {
                                echo "<option value='$type'>$type</option>";
                            }
                        }
                    }
                    echo "</select>";
                    unset($_SESSION['motor_form']['starter'.$i]);

                    //Red border for dropdown box if option is not selected
                    $selected = "";
                    if (isset($_SESSION['motor_form']['protection'.$i])) {
                        $selected = array_key_first($_SESSION['motor_form']['protection'.$i]);
                        if (array_values($_SESSION['motor_form']['protection'.$i])[0]) {
                            echo "<select class='cool_drpdwn' style='border-color:red' name='protection$i' id='protection$i'>";
                        } else {
                            echo "<select class='cool_drpdwn' name='protection$i' id='protection$i'>";
                        }
                    } else {
                        echo "<select class='cool_drpdwn' name='protection$i' id='protection$i'>";
                    }
                    //References xml file for disconnect types and creates dropdown box for each motor
                    $xml = simplexml_load_file("reference.xml");
                    $protector = $xml->xpath("/tables/protection/protect");
                    echo "<option>~Disconnect Type~</option>";
                    foreach ($protector as $protect) {
                        foreach($protect->attributes() as $type) {
                            if ($type == $selected) {
                                echo "<option selected value='$type'>$type</option>";
                            } else {
                                echo "<option value='$type'>$type</option>";
                            }
                        }
                    }
                    echo "</select>";
                    unset($_SESSION['motor_form']['protection'.$i]);

                    echo "<br/>";            
                }
            ?>
            
            <button class="cool_btn" type="submit">Submit</button>
            <a class="cool_btn" href = "panel_form.php">Back</a>  
        </form>

        <br>

    </div>

</body>
</html>
