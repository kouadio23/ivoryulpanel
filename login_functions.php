<?php

    function sanitizeDB($string){

        /*

            Takes in an unsafe string and then sanitizes it for
            the ulpane5_ibex_ul_panel_database database.

        */

        $connection = mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');
        $sanitized = mysqli_real_escape_string($connection, $string);
        mysqli_close($connection);
        return $sanitized;

    }

    function queryDatabase($query) {

        /*  

            Queries the database with that query. NOTE: this function does 
            not have protection against SQL Injection. Use sanitizeDB() 
            for any data that comes from the user.

            Returns the following data array:

                [
                    status: status code (e.g. 200 success, 400 failure, 500 internal failure),
                    data: data from the query (if sucessful),
                    conn: the connection object 
                ]

        */

        $data = [
            "status" => 500,
        ];

        try {

            $connection = mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');

            // Write the query and execute it
            $sql_query = $query; // Need to protect from SQL injection
            $result = mysqli_query($connection, $sql_query);
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Free resources
            mysqli_free_result($result);
            mysqli_close($connection);

            $data = [
                "status" => 400,
                "data" => $data,
                "conn" => $connection,
            ];



        } catch (Exception $e){

            $data = [
                "status" => 400,
                "reason" => $e,
                "conn" => $connection, 
            ];

        }

        return $data;

    }



    function checkLogin() {
        session_start();
        

        // // Check if user is already logged in
        // if (isset($_SESSION['user_id'])) {
        // return $_SESSION['user_id'];
        // }


        $headers = apache_request_headers();
        $token = isset($headers['Authorization']) ? $headers['Authorization'] : '';
      
        if (!$token) {
          return false;
        }

        $conn = mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');

      
        // query the database to find the user with the matching token
        $stmt = $conn->prepare("SELECT user_id, created FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
      
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $user_id = $row['user_id'];
          $created = $row['created'];
      
          // check if the token is expired ( more than 1 hour old)
          if (time() - strtotime($created) > 3600) {
            return false;
          }
      
          // update the token's timestamp to extend its validity
          $stmt = $conn->prepare("UPDATE tokens SET created = NOW() WHERE token = ?");
          $stmt->bind_param("s", $token);
          $stmt->execute();
          
           // close database connection
        mysqli_close($conn);

        // return the user ID
        return $user_id;
    } else {
        // close database connection
        mysqli_close($conn);

        return false;
    }
        //   // return the user ID
        //   return $user_id;
        // } else {
        //   return false;
        // }
      }
      

    // function checkLogin() {   

    //     /*

    //         This function checks if the user is logged in. It returns a
    //         boolean true or false.

    //     */


    //     $isLoggedIn = false;
    //     $failedReason = "";


    //     if (!isset($_COOKIE['username']) || !isset($_COOKIE['password'])){
    //         $isLoggedIn = false;
    //         $failedReason = "no username/password cookies";
    //     } else {

    //         // $connection = mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');

    //         // Write the query and execute it
    //         $username = sanitizeDB($_COOKIE['username']);
    //         $sql_query = "SELECT username, password FROM users WHERE username='$username';";
    //         $queryResult = queryDatabase($sql_query);
    //         $data = $queryResult['data'];


            
    //         // Check the hash against the cookie
    //         $passwordHash = $data[0]['password'];

    //         if (password_verify($_COOKIE['password'], $passwordHash)){
    //             // password checked out. 
    //             $isLoggedIn = true;
    //             $failedReason = null;
    //         } else {
    //             $isLoggedIn = false;
    //             $failedReason = "Invalid Password";
    //         }
            

            

    //         // Delete variables so that they cannot be accessed later
    //         // This does not destroy the data, and so further investigation
    //         // into this should occur. Nonetheless, this is better than nothing
    //         // Please ALWAYS include this disclaimer whereever you are dealing with 
    //         // sensative information
    //         unset($passwordHash);
    //         unset($email);
    //         unset($_POST['password']);
    //         unset($phoneNumber);
    //         unset($sql_query);


    //     }

    //     return $isLoggedIn;
    // }

    function getLoginInfo($conn) {

        
        /*

            Gets the login info of the current logged in user.
            Returns a hashmap with the following data:
                {
                    status: 200 on success, 400 on not logged in,
                    "username": username,
                    "email": email,
                    "phone_number": phone_number,
                }

        */



        $loginInfo = [];

        // First, validate that the user is logged in
        if (!checkLogin($conn)){
            $loginInfo = ["status" => 400];
        } else {

            // // Write the query and execute it
            $username = sanitizeDB($_COOKIE['username']);
            $sql_query = "SELECT * FROM users WHERE username='$username';";
            $queryResult = queryDatabase($sql_query);
            $data = $queryResult['data'];

            
            // Check the hash against the cookie
            $username = $data[0]['username'];
            $email = $data[0]['email'];
            $phone_number = $data[0]['phone_number'];

            $loginInfo = [
                "status" => 200,
                "username" => $username,
                "email" => $email,
                "phone_number" => $phone_number,
            ];

        }

        return $loginInfo;

    }

    function keyIsTaken($table, $column, $value) {

        /*

            Checks if a value is available in a particular table.column 
            For example, it can check if "foo" is an available username
            by running keyIsTaken('users', 'username', "foo"). This could
            also be used to check if an email is already registered.

        */

        $conn = mysqli_connect('ulpanelapp.com', 'ulpane5_php_login', '*_s6+G=8YbZ#', 'ulpane5_ibex_ul_panel_database');

        // Cleans the inputs
        $tableSanitized = mysqli_real_escape_string($conn, $table);
        $columnSanitized = mysqli_real_escape_string($conn, $column);
        $valueSanitized = mysqli_real_escape_string($conn, $value);

        $username = $_POST['username'];
        $result = mysqli_query($conn, "SELECT * FROM $tableSanitized WHERE $columnSanitized = '$valueSanitized'");

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function logUserIn($username, $password){
        // this code check if the user has sent a POST request with the login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // retrieve the username and password from the form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
  
    // validate the inputs
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username) || strlen($username) > 100) {
      http_response_code(400);
      exit('Invalid username');
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $password) || strlen($password) > 100) {
      http_response_code(400);
      exit('Invalid password');
    }
  
    // connect to the database 
    try {
      $pdo = new PDO('mysql:host=ulpanelapp.com,;dbname=ulpane5_ibex_ul_panel_database', 'ulpane5_php_login', '*_s6+G=8YbZ#');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      http_response_code(500);
      exit('Database error: ' . $e->getMessage());
    }
  
    // verify the credentials
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row && password_verify($password, $row['password_hash'])) {
      // generate a unique token
      $token = bin2hex(random_bytes(32));
  
      // store the token in the database, associated with the user
      $stmt = $pdo->prepare('INSERT INTO tokens (username, token) VALUES (:username, :token)');
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':token', $token);
      $stmt->execute();
  
      // return the token to the client.
      echo $token;
      exit;
    } else {
      http_response_code(401);
      exit('Invalid credentials');
    }
  }
    }

?>