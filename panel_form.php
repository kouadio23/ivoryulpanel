<?php
    //Needed when using session variables
    session_start();
    if (isset($_SESSION["completed_graph"])) {
        $_SESSION = array();
    }
?>
<html>
<head>
    <title>UL Panel App</title>
    <script type="text/javascript" src="form.js"></script>    
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
    <link rel="stylesheet" href="style.css">
</head>
<body>  
    <?php
        //Checks for the session variable "errors". If this variable is set, 
        //then it displays these errors at the top of the form.
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
        <h1 class="page_title">UL Panel Generator</h1>    

        <h2 class = "input_title">Input Amount of Motors and Values:</h2>
            
        <!-- Form 1: Enter the number of motors, voltage, phase, and ambient temperature -->
        <form method="post" action="motor_form.php" id="form1">
            <?php
                unset($_SESSION['errors']);

                //If session varriable is set for motors missing, set border for dropdown menu red.
                //Every dropdown menu has this type of if statement.
                if (isset($_SESSION['motorserr'])) {
                    echo "<select class='cool_drpdwn' style='border-color:red' name='motors' id='motors'>";
                } else {
                    echo "<select class='cool_drpdwn' name='motors' id='motors'>";
                }
                //Displays options 1-10 for amount of motors as a dropdown option box
                echo "<option>~Amount of Motors~</option>";
                $motors = array(1,2,3,4,5,6,7,8,9,10);
                foreach($motors as $item){
                    if (isset($_SESSION['motors']) && $item == $_SESSION['motors']) {
                        echo "<option selected value='$item'>$item</option>";
                    } else {
                        echo "<option value='$item'>$item</option>";
                    }
                }
                echo "</select>";

                $xml = simplexml_load_file("reference.xml");

                //If session varriable is set for voltage missing, set border for dropdown menu red.
                if (isset($_SESSION['voltageerr'])) {
                    echo "<select class='cool_drpdwn' style='border-color:red' name='voltage' id='voltage'>";
                } else {
                    echo "<select class='cool_drpdwn' name='voltage' id='voltage'>";
                }
                //Searches through xml file reference.xml for options for panel voltage and
                //displays in dropdown option box
                $voltage = $xml->xpath("/tables/table_50_1/voltage");
                echo "<option>~Voltage~</option>";
                foreach ($voltage as $volts) {
                    foreach($volts->attributes() as $type) {
                        if (isset($_SESSION['voltage']) && $type == $_SESSION['voltage']) {
                            echo "<option selected value='$type'>$type</option>";
                        } else {
                            echo "<option value='$type'>$type</option>";
                        }
                    }
                }
                echo "</select>";

                //If session varriable is set for phase missing, set border for dropdown menu red.
                if (isset($_SESSION['phaseerr'])) {
                    echo "<select class='cool_drpdwn' style='border-color:red' name='phase' id='phase'>";
                } else {
                    echo "<select class='cool_drpdwn' name='phase' id='phase'>";
                }
                //Searches through xml file reference.xml for options for panel phase and
                //displays in a dropdown option box
                $phase = $xml->xpath("/tables/table_50_1/voltage[@type='110-120V']/phase");
                echo "<option>~Phase~</option>";
                foreach ($phase as $pha) {
                    foreach($pha->attributes() as $type) {
                        if (isset($_SESSION['phase']) && $type == $_SESSION['phase']) {
                            echo "<option selected value='$type'>$type</option>";
                        } else {
                            echo "<option value='$type'>$type</option>";
                        }
                    }
                }
                echo "</select>";

                //If session varriable is set for temp missing, set border for dropdown menu red.
                if (isset($_SESSION['temperr'])) {
                    echo "<select class='cool_drpdwn' style='border-color:red' name='temp' id='temp'>";
                } else {
                    echo "<select class='cool_drpdwn' name='temp' id='temp'>";
                }
                //Searches through xml file reference.xml for options for panel ambient temperature and
                //displays in a dropdown option box
                $temperature = $xml->xpath("/tables/table_28_1/temperature");
                echo "<option>~Ambient Temperature~</option>";
                foreach ($temperature as $temp) {
                    foreach($temp->attributes() as $type) {
                        if (isset($_SESSION['temp']) && $type == $_SESSION['temp']) {
                            echo "<option selected value='$type'>$type</option>";
                        } else {
                            echo "<option value='$type'>$type</option>";
                        }
                    }
                }
                echo "</select>";

                //Unsets all session variables for both errors and panel options
                unset($_SESSION["motorserr"]);
                unset($_SESSION["voltageerr"]);
                unset($_SESSION["phaseerr"]);
                unset($_SESSION["temperr"]);
                unset($_SESSION["motors"]);
                unset($_SESSION["voltage"]);
                unset($_SESSION["phase"]);
                unset($_SESSION["temp"]);
            ?>
            <input class="cool_btn" type="submit" value="Submit">
        </form>

        <br>

    </div>

</body>
</html>