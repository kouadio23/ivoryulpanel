<?php
    session_start();
    require_once 'panel_model.php';

    $model = new Panel_Model;
   
    for ($i = 1; $i <= intval($_SESSION["motors"]); $i++){
        // Store errors related to starter inputs
        if ($_POST["starter$i"] == "~Starter Type~") {
            $_SESSION['errors']['starters'] = "Error: Select starter type<br>";
            $_SESSION['motor_form']['starter'.$i] = array($_POST["starter$i"] => true);
        } else {
            $_SESSION['motor_form']['starter'.$i] = array($_POST["starter$i"] => false);
        }
        // Store errors related to disconnect inputs
        if ($_POST["protection$i"] == "~Disconnect Type~") {
            $_SESSION['errors']['protection'] = "Error: Select disconnect type<br>";
            $_SESSION['motor_form']['protection'.$i] = array($_POST["protection$i"] => true);
        } else {
            $_SESSION['motor_form']['protection'.$i] = array($_POST["protection$i"] => false);
        }
        // Attempt to add motor. Retrieve the error returned if there was one and store it as a horsepower error. 
        $error = $model->add_motor($_POST["motor_$i"],$_POST["horsepower$i"],$_POST["starter$i"],$_POST["protection$i"]);
        $_SESSION['motor_form']['horsepower'.$i] = array($_POST["horsepower$i"] => $error);
    }

    if (empty($_SESSION['errors'])) {
        // No errors. Do the graph calculations.
        $model->generate_connecting_components();
        $_SESSION["completed_graph"] = true;
    } else {
        // Errors. Redirect to motor_form.
        ksort($_SESSION['errors']);
        $newURL = "motor_form.php";
        echo "<script type='text/javascript'>document.location.href='{$newURL}';</script>";
    }
    ob_start(); // Start the output buffer

?>

<html>
<head>
    <title>UL Panel App</title>   
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
    <link rel="stylesheet" href="style.css">
</head>    
<body>
    <div class="input_panel">
        <h1 class="page_title">Result</h1><br>  
        <?php
            // Generate the graph.
            echo $model->print_svg();

            //hot fix to prevent too many files being saved
            if (file_exists('cached_pages/')) {
                foreach (new DirectoryIterator('cached_pages/') as $fileInfo) {
                    if ($fileInfo->isDot()) {
                    continue;
                    }
                    if ($fileInfo->isFile() && time() - $fileInfo->getCTime() >= 24*60*60 && $fileInfo->getExtension() == "html") {
                        unlink($fileInfo->getRealPath());
                    }
                }
            }
        ?>

    <br>    
    <a class="cool_btn" onclick=PrintPage()> Print </a> <!--Print Button -->
    <script type="text/javascript">
        function PrintPage() 
        {
            window.print();
        }
    </script>
    <?php
        //Caching code
        $url = $_SERVER["SCRIPT_NAME"];
        $break = Explode('/', $url);
        $file = $break[count($break) - 1];
        $cachefile = ''.substr_replace($file ,"",-4).'_'.rand().'.html';
        while (is_file("cached_pages".$cachefile))
        {
            $cachefile = ''.substr_replace($file ,"",-4).'_'.rand().'.html';
        }
        $cached = fopen("cached_pages/".$cachefile, 'w');
        fwrite($cached, ob_get_contents()); //write the buffer to a file
        fclose($cached);
        ob_end_flush(); // Send the output to the browser
    ?>
    <a class="cool_btn" href = "cached_pages/<?= $cachefile ?>"> Go to saved page </a>    <!--Cached file Button -->
    <br>
    <br> 
    <br> 
    <br>  
    <a class="cool_btn" href = "panel_form.php"> Make another panel </a>    <!--Go back to start Button -->
    <a class="cool_btn" href = "motor_form.php"> Back </a>  <!--Back Button -->
    </div>            

</body>
</html>
