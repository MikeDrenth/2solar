<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
        // Make connection to the database
        include('config.php');
        $db         = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $companyID  = $_GET['client'];

        // Query for getting the client name and logo
        $query      = $db->prepare("SELECT company_name, company_logo FROM solar_company WHERE client_id = '$companyID'");
        $query->execute();
        $result     = $query->get_result();
        $r          = $result->fetch_array(MYSQLI_ASSOC); 

        // Save results in variable
        $clientName = $r['company_name'];
        $clientLogo = $r['company_logo'];

        //Get the user data, set a session if account info is found in database
        session_start();
        if (!empty($_POST)) {
            if ( isset( $_POST['emailaddress'] ) && isset( $_POST['password'] ) ) {
                // Getting submitted user data from database
                $username = $_POST['emailaddress'];
                $password = $_POST['password'];
                $stmt     = "";
                if(!($stmt = $db->prepare("SELECT * FROM solar_users WHERE user_email = ?"))){
                    die( "Error preparing: (" .$db->errno . ") " . $db->error);
                }
                if(!($stmt->bind_param("s", $username))){
                    die( "Error in bind_param: (" .$db->errno . ") " . $db->error);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $user   = $result->fetch_array();

                // Check if password matches the password in DB, then set session
                //if ( password_verify( $_POST['password'], $user->user_pass ) ) {
                if($_POST['password'] == $user['user_pass']) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['client_id'] = $user['client_id'];
                    if(!empty($_SESSION['user_id'])) {
                        header("location:company.php");
                    }
                }
            }
        }
    ?>
    <div id="pageWrapper" class="d-flex flex-column h-100 justify-content-center">
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        
                        <h1>Welcome <?=$clientName?>, please login below</h1>
                    </div>
                </div>
            </div>
        </header>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="pageForm d-flex justify-content-center flex-column">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $clientLogo ).'"/>'; ?>
                            </div>
                            <div class="col-12 col-md-6">
                                <h2>User login</h2>
                                <form action="" method="post">
                                    <input type="email" placeholder="Emailaddress" name="emailaddress" required><br><br>
                                    <input type="password" placeholder="Password" name="password" required><br><br>
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>