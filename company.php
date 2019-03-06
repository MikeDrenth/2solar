<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
        // Start Session
        session_start();

        // Make connection to the database
        include('config.php');
        $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Get company ID by SESSION ID
        if(!empty($_SESSION['user_id'] && !empty($_SESSION['client_id']))) {
            $companyID = $_SESSION['client_id'];
        }

        // Query for getting the client name and logo
        $query = $db->prepare("SELECT * FROM solar_company WHERE client_id = '$companyID'");
        $query->execute();
        $result = $query->get_result();
        $r = $result->fetch_array(MYSQLI_ASSOC); 

        // Save results in variable
        $clientName   = $r['company_name'];
        $clientLogo   = $r['company_logo'];
        $deadline     = $r['deadline'];
        $previewOrder = $r['preview_order'];
        $writingPaper = $r['writing_paper'];
        $priceList    = $r['price_list'];
        $listLogin    = $r['last_login'];
        $progress     = $r['progress'];

        // If form is submitted, update or insert results in datbase
        if (!empty($_POST)) {
            $orderPreview   = (!empty($_POST['orderPreview'])) ? $_POST['orderPreview'] : '';
            $companyLogo    = (!empty($_POST['companyLogo'])) ? $_POST['companyLogo'] : '';
            $writingPaper   = (!empty($_POST['writingPaper'])) ? $_POST['companyLogo'] : '';
            $priceList      = (!empty($_POST['priceList'])) ? $_POST['priceList'] : '';
            $currentDate    = date("Y/m/d");
            // Check connection
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            } 

            // TODO: Update the current client with client_id
            $result = $db->prepare('
                UPDATE
                    solar_company (
                        company_logo,
                        preview_order,
                        writing_paper,
                        price_list,
                        last_update
                    )
                VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ');   

            $result->bind_param('bbbbs', $companyLogo, $orderPreview, $$writingPaper, $priceList, $currentDate);
            $result->execute();
        }
    ?>
    <div id="clientWrapper" class="d-flex flex-column h-100 justify-content-center">
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1>Hi <?=$clientName?></h1>
                    </div>
                </div>
            </div>
        </header>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="clientContent">
                        <div class="row align-items-center">
                            <div class="deadLine">
                                <div class="col-12">
                                    <?php 
                                        $deadline = strtotime($deadline);
                                        $deadline = date("m-d-Y");
                                    ?>
                                    <p>Deadline: <?=$deadline;?></p>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>Please upload or update the missing fields below.</p>
                            </div>
                            <div class="col-12 col-md-4">
                                <form action="" method="post">
                                    <?php $isValid = ($clientLogo == '') ? 'missing' : 'complete'; ?>
                                    <div class="upload-btn-wrapper <?=$isValid;?>">
                                        <button class="btn">Upload a logo</button>
                                        <input class="fileUpload" type="file" name="companyLogo"><br><br>
                                    </div>
                                    <?php $isValid = ($previewOrder == '') ? 'missing' : 'complete'; ?>
                                    <div class="upload-btn-wrapper">
                                        <button class="btn">Preview order document</button>
                                        <input class="fileUpload"  type="file" name="orderPreview"><br><br>
                                    </div>
                                    <?php $isValid = ($writingPaper == '') ? 'missing' : 'complete'; ?>
                                    <div class="upload-btn-wrapper">
                                        <button class="btn">Writing paper</button>
                                        <input class="fileUpload"  type="file" name="writingPaper"><br><br>
                                    </div>
                                    <?php $isValid = ($priceList == '') ? 'missing' : 'complete'; ?>
                                    <div class="upload-btn-wrapper">
                                        <button class="btn">Price list</button>
                                        <input class="fileUpload"  type="file" name="priceList"><br><br>
                                    </div>
                                    <input type="submit" value="Save">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>