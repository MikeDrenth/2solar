<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
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
        } else {
            //$companyID = 101;
        }

        // Query for getting the current client info
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

            // TODO: If there was already a document in the record, then update that current file in the database, if not, then insert.
            $result = $db->prepare('
                INSERT INTO 
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

            $result->bind_param('bbbbs', $companyLogo, $orderPreview, $writingPaper, $priceList, $currentDate);
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
                <div class="col-12">
                    <div class="clientContent">
                        <div class="row">
                            <div class="deadLine">
                                <div class="col-12">
                                    <?php 
                                        $deadlineStr = strtotime($deadline);
                                        $deadlineStr = date("m-d-Y", $deadlineStr);
                                    ?>
                                    <p>Deadline: <?=$deadlineStr;?></p>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>Upload a.u.b. onderstaand de benodigde documenten.</p>
                            </div>
                            <div class="col-12 col-md-8">
                                <form action="" method="post" name="uploadData" id="uploadData">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <?php $isValid = ($clientLogo == '') ? 'missing' : 'complete'; ?>
                                            <div class="upload-btn-wrapper <?=$isValid;?>">
                                                <i class="fas fa-check"></i>
                                                <div class="text">Upload bedrijfs logo</div>
                                                <input id="companyLogo" class="fileUpload" type="file" name="companyLogo">
                                                <div class="infoIcon"><i class="fas fa-info-circle"></i></div>
                                                <div class="extraInfo">
                                                    <p>Upload hier uw bedrijfs logo. Gelieve deze aan te leveren in PNG bestand met een maximale upload van 5MB</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <?php $isValid = ($previewOrder == '') ? 'missing' : 'complete'; ?>
                                            <div class="upload-btn-wrapper <?=$isValid;?>">
                                                <i class="fas fa-check"></i>
                                                <div class="text">Upload offerte voorbeeld</div>
                                                <input class="fileUpload"  type="file" name="orderPreview">
                                                <div class="infoIcon"><i class="fas fa-info-circle"></i></div>
                                                <div class="extraInfo">
                                                    <p>Upload hier een voorbeeld van huidige offerte document.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <?php $isValid = ($writingPaper == '') ? 'missing' : 'complete'; ?>
                                            <div class="upload-btn-wrapper <?=$isValid;?>">
                                                <i class="fas fa-check"></i>
                                                <div class="text">Upload briefpapier</div>
                                                <input class="fileUpload"  type="file" name="writingPaper">
                                                <div class="infoIcon"><i class="fas fa-info-circle"></i></div>
                                                <div class="extraInfo">
                                                    <p>Upload hier uw briefpapier, deze wordt gebruikt voor het maken van de offertes.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                        <?php $isValid = ($priceList == '') ? 'missing' : 'complete'; ?>
                                            <div class="upload-btn-wrapper  <?=$isValid;?>">
                                                <i class="fas fa-check"></i>
                                                <div class="text">Upload prijzenlijst</div>
                                                <input class="fileUpload"  type="file" name="priceList">
                                                <div class="infoIcon"><i class="fas fa-info-circle"></i></div>
                                                <div class="extraInfo">
                                                    <p>Upload hier een CSV bestand met de prijzenlijst.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" value="Save">
                                </form>
                            </div>
                            <div class="col-12 col-md-4">
                                <?php
                                    // Get total days left before deadline
                                    date_default_timezone_set('Europe/Warsaw');
                                    $datetime1 = new DateTime($deadline);
                                    $datetime2 = new DateTime(date("d/m/Y"));
                                    $interval = $datetime1->diff($datetime2);
                                    $daysLeft = $interval->format('%a');
                                ?>
                                <p><?=$daysLeft?> dagen om benodigde informatie te leveren.</p>
                                <h3>Vooruitgang</h3>
                                <div class="bar-one bar-con progress">
                                    <?php
                                        $percentagePerValid = 25;
                                        $totalFields        = 4;
                                        $notEmptyField      = 0;
                                        $progressPercentage = 0;
                                        if($priceList !== '') {
                                            $notEmptyField++;
                                        }
                                        if($writingPaper !== '') {
                                            $notEmptyField++;
                                        }
                                        if($priceList !== '') {
                                            $notEmptyField++;
                                        }
                                        if($previewOrder !== '') {
                                            $notEmptyField++;
                                        }
                                        $progressPercentage = $percentagePerValid * $notEmptyField;
                                    ?>
                                    <div class="bar progress-bar" data-percent="<?=$progressPercentage?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="custom.js" type="text/javascript"></script>
</body>
</html>