<!DOCTYPE html>
<html lang="en">
<head>
    <title>Updating Appointment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="home_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <script>
    function keypresshandlerNUM(event)
    {
         var charCode = event.keyCode;
         //numeric character range
         if (charCode > 31 && (charCode < 48 || charCode > 57))
             return false;
    }
    </script>
    <style>
    .fakeimg {
        height: 200px;
        background: #aaa;
    }

    </style>
</head>
<body>
<?php
        $menuFile = "menu.php";
        if(file_exists($menuFile)){
                include $menuFile;
        }//opens a file and reads it
    ?>

    <div class="container-fluid" style="margin-top:2%; margin-bottom:2%">
        <div class="row">
            <div class="col-sm-2">
                
                <div class="card">
                <?php
                        $file = "driving_menu.php";
                        if(file_exists($file)){
                            include "driving_menu.php";
                        }//opens a file and reads it
                    ?>
                </div>

            </div>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-8">
                        <h2>Look up</h2>
                        <p>To look for a patient type in the <br>First and Last Name, <b>OR</b> phone number <br> of the patient.</p>
                        <?php

                        $Date = $_POST["date"];
                        $Time = date('h:i A', strtotime($_POST["times"]));
                        $userinputID = $_POST["PatientID_Input"];
                        $SQLID = $_POST["SQLID"];
                        $FName = $_POST["FName"];
                        $LName = $_POST["LName"];
                        $Type = $_POST["Appt_Type"];
                        $PN = $_POST["patientPN"];
                        $sql = "SELECT Patientid from Patients  where Patientid = ". $SQLID;
                        $result = mysqli_query($conn, $sql);
                        $sqlquery = mysqli_fetch_assoc($result);
                        if($SQLID == $userinputID && $sqlquery["Patientid"] == $userinputID)
                        {
                            $sql = "UPDATE patients SET PatientFNAME = \"" . $FName . "\", PatientLNAME = \"" . $FLame . "\", APPDATE = \"" . $Date . "\", 
                            PatientTIME = \"" . $Time . "\", HYGIENIST = \"" . $Type . "\" from patients where Patientid = " . $userinputID;
                            mysqli_query($conn, $sql);
                            echo '<h5>Success!! ' . $_POST["FName"] . " " . $_POST["LName"] . " appointment is on " . $Date . " at " . $Time . "!</h5>";
                        }
                        else
                        {
                            echo '<h4>Error! PatientID did not match with patient!! Please try again!</h4>';
                        }


                    ?>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    

                    <form action="menu_appt.php" method="post">
                        <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" required></p>
                        <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" required ><p>
                        <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
                        <p>Patient Phone Number: <input type="tel" id= "patientPN" name="patientPN" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress = 'return keypresshandlerNUM(event)'></p>
                        <!-- Add password -->
                        <p>Password: <input type="password" name="password" required><p>
                        <div>
                            <input class="btn btn-secondary" id="ClearForm" type="reset" name="reset" value="Clear Form">
                            <input class="btn btn-primary" type="submit" name="submit" value="Search Patient">
                        </div>
                    </form>
                    </div>
                    
                    <div>
                    <script src="build/js/intlTelInput.js"></script>
                    <script>
                        var input = document.querySelector("#patientPN");
                        window.intlTelInput(input, {
                        hiddenInput: "patientPN",
                        utilsScript: "build/js/utils.js",
                        });
                    </script>
                    </div>
                </div>
                
        </div>
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>
