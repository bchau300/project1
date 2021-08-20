<!DOCTYPE html>
<html lang="en">
<head>
    <title>Narrow Selection</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="home_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <script>
    function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
        };
        xmlhttp.open("GET","getuser.php?q="+str,true);
        xmlhttp.send();
    }
    }

    function lettersOnly(evt) {
       evt = (evt) ? evt : event;
       var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
          ((evt.which) ? evt.which : 0));
       if (charCode > 31 && (charCode < 65 || charCode > 90) &&
          (charCode < 97 || charCode > 122)) {
          return false;
       }
       return true;
     }

    function DelButton(str)
    {
        var Delbutton = document.getElementById("delete");
        if(str.toLowerCase() =="confirm")
        {
            Delbutton.style.display = "block";
        }
        else
        {
            Delbutton.style.display = "none";
        }
    }
    </script>
    <style>
    .fakeimg {
        height: 200px;
        background: #aaa;
    }
    body {
    background-color: #C6DEFF;
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
                            include $file;
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
                            #This grabs the ID number from the previous page
                            $sql = "SELECT * FROM patients where Patientid= \"" . $_POST["PastPat"] . "\" AND PatientLOCATION = 'Portsmouth'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $TimeFormat = "h:i A";
                            $count = 0;

                            #This checks if the patient has multiple session in one day
                            $checkSQL = "SELECT APPDATE from patients where PatientFNAME = \"". $row["PatientFNAME"] . "\" AND PatientLNAME = \"". $row["PatientLNAME"] . "\" AND APPDATE = \"". $row["APPDATE"] . "\" AND PatientLOCATION = 'Portsmouth'";
                            $checkResult = mysqli_query($conn, $checkSQL);
                            foreach($result as $printing)
                            {
                                $ArrayofTime[$count] = $printing["PatientTIME"];
                                $count++;
                            }

                            #this first IF statement is finding 1 Date
                            if(mysqli_num_rows($checkResult) == 1) 
                            {
                                if(isset($_POST["Delete"]))
                                {
                                    switch($row["PATSTATUS"])
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
                                    $PatientTime = date($TimeFormat, strtotime($row["PatientTIME"]));
                                    echo '<form action="confirmDelete.php" method="post">';
                                    echo '<p>Patient First Name: <input type="string" name="FName" value = ' . $row["PatientFNAME"] . ' placeholder="First Name" required readonly></p>';
                                    echo '<p>Patient Last Name: <input type="string" name="LName" value = ' . $row["PatientLNAME"] . ' placeholder="Last Name" required readonly></p>';
                                    echo $type;
                                    echo '<p>Patient Appointment Date: <input type="string" autocomplete="off" name="date" class="datepicker" value = ' . $row["APPDATE"] . ' placeholder="05-25-2021" required onchange="showUser(this.value)"></p>';
                                    echo '<label for="time">Appointment Time:</label> <select name="patientTimes" id="patientTimes" value= ' . $PatientTime . ' required>
                                    <option value = '. $PatientTime . ' selected> '. $PatientTime .'</option>
                                    </select><br>';
                                    echo '<p>Patient Location: <input type="string" name="patientLocation" value = ' . $row["PatientLOCATION"] . ' placeholder="Portsmouth" required readonly></p>';
                                    echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "' . $row["PatientPN"] . '" placeholder="+17574852222" required readonly onkeypress = "return keypresshandlerNUM(event)"></p>';
                                    echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = ' . $row["Patientid"] . ' readonly>';
                                    echo'<p>Type "Confirm" to remove the patient: <input type="string" id="confirmation" name="confirmation" onkeypress = "return lettersOnly(event)" onkeyup="DelButton(this.value)" required>
                                    <div>
                                        <input class="btn btn-secondary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search"><p></p>
                                        <input class="btn btn-primary" style="Display:none" id="delete" type="submit" name="delete" value="Delete Patient">
                                    </div></form>';
                                    
                                    
                                }
                                else
                                {
                                    #I need an update page
                                    switch($row["PATSTATUS"])
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
                                    $PatientTime = date($TimeFormat, strtotime($row["PatientTIME"]));
                                    echo '<form action="confirmDelete.php" method="post">';
                                    echo '<p>Patient First Name: <input type="string" name="FName" value = ' . $row["PatientFNAME"] . ' placeholder="First Name" required></p>';
                                    echo '<p>Patient Last Name: <input type="string" name="LName" value = ' . $row["PatientLNAME"] . ' placeholder="Last Name" required></p>';
                                    echo $type;
                                    echo '<div id="calendar">
                                    <p>Appointment Date: <input type="text" name="date" id="date" class="date" value = ' . $row["APPDATE"] . ' placeholder="05-25-2021" autocomplete="off" required onchange="showUser(this.value)"></p>
                                    </div>';
                                    echo '<label for="time">Appointment Time:</label>
                                    <select name="times" id="times" value= ' . $PatientTime . ' required>
                                    <option value = '. $PatientTime . ' selected> '. $PatientTime .'</option>
                                    <div id="times">
                                    </select>
                                    </select>
                                    <br>';
                                    echo '<p>Patient Location: <input type="string" name="patientLocation" value = ' . $row["PatientLOCATION"] . ' placeholder="Portsmouth" required readonly></p>';
                                    echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "' . $row["PatientPN"] . '" placeholder="+17574852222" ></p>';
                                    echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = ' . $row["Patientid"] . ' readonly ></p>';
                                    echo'<p>Type "Confirm" to remove the patient: <input type="string" id="confirmation" name="confirmation" onkeypress = "return lettersOnly(event)" onkeyup="DelButton(this.value)" required>
                                    <div>
                                        <input class="btn btn-secondary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search"><p></p>
                                        <input class="btn btn-primary" style="Display:none" id="delete" type="submit" name="delete" value="Delete Patient">
                                    </div></form>';
                                }
                            }
                            #Second if statement is more than 1 result
                            else if(mysqli_num_rows($checkResult) > 1)
                            {
                                #Note cannot delete with multiple sessions
                                switch($row["PATSTATUS"])
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

                                $time1 = date($TimeFormat, strtotime($ArrayofTime[0]));
                                $time2 = date($TimeFormat, strtotime($ArrayofTime[count($ArrayofTime)-1]. " +30 MINUTE"));
                                echo '<form action="remove_and_update.php" method="post">';
                                echo '<h5>To reschedule with multiple sessions must delete and re-add or delete the schedule</h5>';
                                echo '<p>Patient First Name: <input type="string" name="FName" value = ' . $row["PatientFNAME"] . ' placeholder="First Name" required></p>';
                                echo '<p>Patient Last Name: <input type="string" name="LName" value = ' . $row["PatientLNAME"] . ' placeholder="Last Name" required></p>';
                                echo $type;
                                echo '<div id="calendar">
                                <p>Appointment Date: <input type="text" name="date" id="date" class="date" value = ' . $row["APPDATE"] . ' placeholder="05-25-2021" autocomplete="off" required onchange="showUser(this.value)"></p>
                                </div>';
                                echo '
                                <p>Patient Appointment Start Time: <input type="string" name="StartTime" value = "'. $time1 .'" placeholder="10:00AM" required readonly><p>
                                <p>Patient Appointment End Time: <input type="string" name="EndTime" value = "'. $time2 .'" placeholder="10:30AM" required readonly><p>';
                                echo '<p>Patient Location: <input type="string" name="patientLocation" value = ' . $row["PatientLOCATION"] . ' placeholder="Portsmouth" required readonly></p>';
                                echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "' . $row["PatientPN"] . '" placeholder="+17574852222" ></p>';
                                echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = ' . $row["Patientid"] . ' readonly ></p>';
                                echo '<p>Type "Confirm" to remove the patient: <input type="string" id="confirmation" name="confirmation" onkeypress = "return lettersOnly(event)" onkeyup="DelButton(this.value)" required>
                                <div>
                                    <input class="btn btn-primary" style="Display:none" id="Readdsubmit" type="submit" name="Readdsubmit" value="Delete and Re-Add Patient">
                                    <input class="btn btn-secondary" style="Display:none" id="Delete" type="submit" name="Delete" value="Delete Patient">
                                    <input class="btn btn-secondary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search"><p></p>  
                                </div></form>';                                    
                            }



                            ?>

                        </form>
                    </div>
                
                    
                    </div>
                </div>
                
        </div>
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>
