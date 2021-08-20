<!DOCTYPE html>
<html lang="en">
<head>
    <title>Confirmation Deletion</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="home_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="build/js/date.js"></script>
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
    <!--This PHP right here is setting up all the information/data sets-->
    <?php

    $host = "127.0.0.1";
    $user = "root";
    $password = "test123";
    $database = "test";
    // Create connection
    $conn = mysqli_connect($host, $user, $password, $database);
    // Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    ?>
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
                        <h2>Remove Appointment</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    
                        <?php
                            #DISPLAY THE SUCCESS MESSAGE HERE AND INPUT INTO THE SQL
                            $ID_SQL = $_POST["PatientID"];
                            $FName = $_POST["FName"];
                            $LName = $_POST["LName"];
                            $date = $_POST["date"];

                            $sql = "SELECT Patientid from Patients where PatientID = " . $ID_SQL;
                            $result = mysqli_query($conn, $sql);
                            $fetchresult = mysqli_fetch_assoc($result);
                            

                            $TimeFormat = "h:i A";
                            switch($_POST["Appt_Type"])
                            {
                                case 0:
                                    $type = '<label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                                    <option value="0"Selected>General Cleaning</option>
                                    </select>';
                                    break;
                                case 1:
                                    $type = '<label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                                    <option value="1"Selected>Dr.Lesinski</option>
                                    </select>';
                                    break;
                            }

                            if(mysqli_num_rows($result) > 0)
                            {
                                $UserID = mysqli_fetch_assoc($result);


                                
                                if (mysqli_num_rows($result) == 1)
                                {
                                    if($ID_SQL == $fetchresult["Patientid"])
                                    {
                                        $sql = "DELETE FROM PATIENTS WHERE PATIENTID = " . $ID_SQL . " AND PatientFNAME = \"" . $FName . "\" AND PatientLNAME = \"" . $LName . "\"";
                                        mysqli_query($conn, "DELETE FROM SIXMONTHREMINDER WHERE PATIENTID = " . $ID_SQL . "AND PatientFNAME = \"" . $FName . "\" AND PatientLNAME = \"" . $LName . "\"");
                                        $result = mysqli_query($conn, $sql);
                                        if($result)
                                        {
                                            echo "<h5>Success! " . $FName . " " . $LName . "'s appointment has been deleted! <br>Search another patient enter the information below</h5>";
                                            echo '<form action="menu_appt.php" method="post">
                                            <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" required></p>
                                            <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" required ><p>
                                            <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
                                            <p>Looking for past appointment? <input type="checkbox" id="pastAppt" name="pastAppt" value="Yes"></p>
                                            <!-- Add password -->
                                            <p>Password: <input type="password" name="password" required><p>
                                            <div>
                                                <input class="btn btn-secondary" id="ClearForm" type="reset" name="reset" value="Clear Form">
                                                <input class="btn btn-primary" type="submit" name="submit" value="Search Patient">
                                            </div>
                                        </form>';
                                        }
                                        else
                                        {
                                            echo '
                                            <form action="confirmDelete.php" method="post">
                                                <p>Error! Information does not match! Please try again!!</p>';
                                                $PatientTime = date($TimeFormat, strtotime($_POST["times"]));
                                                echo '<p>Patient First Name: <input type="string" name="FName" value = '. $_POST["FName"].' placeholder="First Name" required readonly></p>';
                                                echo '<p>Patient Last Name: <input type="string" name="LName" value = '. $_POST["LName"].' placeholder="Last Name" required readonly></p>';
                                                echo $type;
                                                echo '<p>Patient Appointment Date: <input type="string" autocomplete="off" name="date" class="datepicker" value = '. $_POST["date"].' placeholder="05-25-2021" required onchange="showUser(this.value)"></p>';
                                                echo '<label for="time">Appointment Time:</label> <select name="patientTimes" id="patientTimes" value= ' . $PatientTime . ' required>
                                                <option value = '. $PatientTime . ' selected> '. $PatientTime .'</option>
                                                </select><br>';
                                                echo '<p>Patient Location: <input type="string" name="patientLocation" value = '. $_POST["patientLocation"].' placeholder="Portsmouth" required readonly></p>';
                                                echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "'. $_POST["patientPN"].'" placeholder="+17574852222" required readonly onkeypress = "return keypresshandlerNUM(event)"></p>';
                                                echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = '. $_POST["patientID"].' readonly>';
                                                
                                            echo'
                                            <p>Enter the Patient ID to confirm deleting off from the system<input type="string" name="PatientID_Input" required>
                                            <div>
                                                <input class="btn btn-primary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search">
                                                <input class="btn btn-secondary" id="Delete" type="submit" name="submit" value="Delete Patient">
                                            </div>
                    
                                        </form>';
                                        }
                                    }
                                    else
                                    {
                                        echo '
                                        <form action="confirmDelete.php" method="post">
                                            <p>Error! Information does not match! Please try again!</p>';
                                            $PatientTime = date($TimeFormat, strtotime($_POST["patientTimes"]));
                                            echo '<p>Patient First Name: <input type="string" name="FName" value = '. $_POST["FName"].' placeholder="First Name" required readonly></p>';
                                            echo '<p>Patient Last Name: <input type="string" name="LName" value = '. $_POST["LName"].' placeholder="Last Name" required readonly></p>';
                                            echo $type;
                                            echo '<p>Patient Appointment Date: <input type="string" autocomplete="off" name="date" class="datepicker" value = '. $_POST["date"].' placeholder="05-25-2021" required onchange="showUser(this.value)"></p>';
                                            echo '<label for="time">Appointment Time:</label> <select name="patientTimes" id="patientTimes" value= ' . $PatientTime . ' required>
                                            <option value = '. $PatientTime . ' selected> '. $PatientTime .'</option>
                                            </select><br>';
                                            echo '<p>Patient Location: <input type="string" name="patientLocation" value = '. $_POST["patientLocation"].' placeholder="Portsmouth" required readonly></p>';
                                            echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "'. $_POST["patientPN"].'" placeholder="+17574852222" required readonly onkeypress = "return keypresshandlerNUM(event)"></p>';
                                            echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = '. $_POST["PatientID"].' readonly>';
                                            
                                        echo'
                                        <p>Enter the Patient ID to confirm deleting off from the system<input type="string" name="PatientID_Input" required>
                                        <div>
                                            <input class="btn btn-primary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search">
                                            <input class="btn btn-secondary" id="Delete" type="submit" name="submit" value="Delete Patient">
                                        </div>
                
                                    </form>';
                                    }
                                }
                            }
                            else
                            {
                                    echo '
                                    <form action="confirmDelete.php" method="post">
                                        <p>Error! Information does not match! Please try again!</p>';
                                        $PatientTime = date($TimeFormat, strtotime($_POST["patientTimes"]));
                                        echo '<p>Patient First Name: <input type="string" name="FName" value = '. $_POST["FName"].' placeholder="First Name" required readonly></p>';
                                        echo '<p>Patient Last Name: <input type="string" name="LName" value = '. $_POST["LName"].' placeholder="Last Name" required readonly></p>';
                                        echo $type;
                                        echo '<p>Patient Appointment Date: <input type="string" autocomplete="off" name="date" class="datepicker" value = '. $_POST["date"].' placeholder="05-25-2021" required onchange="showUser(this.value)"></p>';
                                        echo '<label for="time">Appointment Time:</label> <select name="patientTimes" id="patientTimes" value= ' . $PatientTime . ' required>
                                        <option value = '. $PatientTime . ' selected> '. $PatientTime .'</option>
                                        </select><br>';
                                        echo '<p>Patient Location: <input type="string" name="patientLocation" value = '. $_POST["patientLocation"].' placeholder="Portsmouth" required readonly></p>';
                                        echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "'. $_POST["patientPN"].'" placeholder="+17574852222" required readonly onkeypress = "return keypresshandlerNUM(event)"></p>';
                                        echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = '. $_POST["PatientID"].' readonly>';
                                        
                                    echo'
                                    <p>Enter the Patient ID to confirm deleting off from the system<input type="string" name="PatientID_Input" required>
                                    <div>
                                        <input class="btn btn-primary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search">
                                        <input class="btn btn-secondary" id="Delete" type="submit" name="submit" value="Delete Patient">
                                    </div>
            
                                </form>';

                            }
                        ?>

                </div>
        </div>
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>
