<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="home_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="date/css/bootstrap-datepicker.css">
    <script type="text/javascript" src="date/js/bootstrap-datepicker.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <script>
    function showUser(str) {
        if (str == "") 
        {
            document.getElementById("times").innerHTML = "";
            return;
        } 
        else
        {
        str1 = document.getElementById("Appt_Type").value;

        if(str1!= "" && str != "")
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("times").innerHTML = this.responseText;
            }
            };
            xmlhttp.open("GET","getTimesChange.php?date="+str+"&type="+str1,true);
            xmlhttp.send();
        }

        }
    }

    function DelButton(str)
    {
        var Readdsubmit = document.getElementById("Readdsubmit");
        var Removesubmit = document.getElementById("Removesubmit");
        if(str.toLowerCase() =="confirm")
        {
            Removesubmit.style.display = "block";
            Readdsubmit.style.display = "block";
        }
        else
        {
            Removesubmit.style.display = "none";
            Readdsubmit.style.display = "none";
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

    function keypresshandlerNUM(event)
    {
         var charCode = event.keyCode;
         //numeric character range
         if (charCode > 31 && (charCode < 48 || charCode > 57))
             return false;
    }

    </script>
    <script src="build/js/intlTelInput.js"></script>

                    
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
    <!--This PHP right here is setting up all the information/data sets-->
    <?php
    #This function will output the inputs from search_appt.php
    function ErrorInputs()
    {
        echo '<form action="menu_appt.php" method="post">
        <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" required></p>
        <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" required ><p>
        <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
        <p>Patient Phone Number: <input type="tel" id= "patientPN" name="patientPN" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress = "return keypresshandlerNUM(event)"></p>
        <!-- Add password -->
        <p>Looking for past appointment? <input type="checkbox" id="pastAppt" name="pastAppt" value="Yes"></p>
        <p>Password: <input type="password" name="password" required><p>
        <div>
            <input class="btn btn-secondary" id="ClearForm" type="reset" name="reset" value="Clear Form">
            <input class="btn btn-primary" type="submit" name="submit" value="Search Patient">
        </div>
        </form>';
    }
    $dt = new DateTime;
    if (isset($_GET['year']) && isset($_GET['week'])) {
        $dt->setISODate($_GET['year'], $_GET['week']);
    } else {
        $dt->setISODate($dt->format('o'), $dt->format('W'));
    }
    $year = $dt->format('o');
    $week = $dt->format('W');
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
                        <h2>Patient Infomation</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    
                    <?php
                        #DISPLAY THE SUCCESS MESSAGE HERE AND INPUT INTO THE SQL
                        #$_POST["patientLocation"]
                        $TimeFormat = 'h:iA';
                        $PatientPN = "";
                        date_default_timezone_set('EST');
                        if(isset($_POST["FName"]) && isset($_POST["LName"]) && isset($_POST["password"]) )
                        {   
                                
                            if(isset($_POST["PatientPN"]))
                            {
                                $PatientPN = $_POST["PatientPN"];
                            }
                            if(isset($_POST["pastAppt"]))
                            {
                                $Past = 'Yes';
                            }
                            else
                            {
                                $Past = 'No';
                            }
                            $Pwd = $_POST["password"];
                            $FName = $_POST["FName"];
                            $LName = $_POST["LName"];

                            #grab password here
                            $sqlBuff = "SELECT Pass from Portsmouth";
                            $resultBuff = mysqli_query($conn, $sqlBuff);
                            $Checking = mysqli_fetch_assoc($resultBuff);
                            #The cycle of IF's
                            #Check for the password
                            if($Pwd == $Checking["Pass"])
                            {
                                if($Past == 'Yes')
                                {
                                    $sql = "SELECT * FROM patients where PatientFNAME= \"" . $FName . "\"" ."AND PatientLNAME = \"". $FLame . "\" AND PatientLOCATION= 'Portsmouth'";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) 
                                    {
                                        //
                                        foreach($result as $printing)
                                        {
                                            $ArrayofTime[$count] = $printing["APPDATE"];
                                            $ArrayofLName[$count] = $printing["PatientLNAME"];
                                            $ArrayofFName[$count] = $printing["PatientFNAME"];
                                            $ArrayofIDs[$count] = $printing["Patientid"];
                                        }
                                        #this just show the patient history
                                        echo'<p class="DaysofWeek">Patient Appointment History</p>
                                        <div class="intercard_table_div">
                                            <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
                                                <tbody>';
                                        echo '<form action="menu_appt.php" method="post">';
                                        for($Counting = 0; $Counting < count($ArrayofTime)-1; $Counting)
                                        {
                                            echo '<tr><td><input type="radio" value=' . $ArrayofIDs[$Counting] . ' name="PastPat" id=' . $ArrayofIDs[$Counting] . 
                                            ' ><label for=' . $ArrayofIDs[$Counting] . '> '. $ArrayofFName[$Counting] . ' ' . $ArrayofLName[$Counting] . 
                                            ' <br>Appointment date: ' . $ArrayofDate[$Counting] .'  <br>Appointment Time: ' .  $ArrayofTime[$Counting] . ' </label></td></tr>';
                                        }
                                        echo '</form></tbody></table></div>';
                                    }
                                    else
                                    {
                                        echo '<h5>No Patient has been found!<h5>';
                                        ErrorInputs();
                                    }
                                }
                                #This statement will have either ONE appointment OR Multiple Appointment on the same day OR
                                #List all the appointment due to having multiple dates
                                else if ($Past == 'No')
                                {
                                    $sql = "SELECT * FROM patients where PatientFNAME= \"" . $FName . "\"" ."AND PatientLNAME = \"". $LName . "\" AND PatientLOCATION = 'Portsmouth'";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) 
                                    {
                                        $count = 0;
                                        foreach($result as $printing)
                                        {
                                            $ArrayofDate[$count] = $printing["APPDATE"];
                                            $ArrayofTime[$count] = $printing["PatientTIME"];
                                            $ArrayofLName[$count] = $printing["PatientLNAME"];
                                            $ArrayofFName[$count] = $printing["PatientFNAME"];
                                            $ArrayofIDs[$count] = $printing["Patientid"];
                                            $ArrayofPN[$count] = $printing["PatientPN"];
                                            $ArrayofStatus[$count] = $printing["PATSTATUS"];
                                            $count++;
                                        }

                                        
                                        #only one appointment is found (Done)
                                        if($count == 1)
                                        {
                                            #The first index of an array is 0
                                            $count--;
                                            $temp = strlen($ArrayofPN[$count]);
                                            #Country Codes
                                            switch($temp)
                                            {
                                                case 12:
                                                    $PatientPN = substr($temp, 1, 11);
                                                    break;
                                                case 13:
                                                    $PatientPN = substr($temp, 2, 12);
                                                    break;
                                                case 14:
                                                    $PatientPN = substr($temp, 3, 13);
                                                    break;
                                                case 15:
                                                    $PatientPN = substr($temp, 4, 14);
                                                    break;
                                            }
                                            switch($ArrayofStatus[$count])
                                            {
                                                case 0:
                                                    $type = '<label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                                                    <option value="0"Selected>General Cleaning</option>
                                                    <option value="1">Dr.Lesinski</option>
                                                    </select>';
                                                    break;
                                                case 1:
                                                    $type = '<label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                                                    <option value="0">General Cleaning</option>
                                                    <option value="1"Selected>Dr.Lesinski</option>
                                                    </select>';
                                                    break;
                                            }
                                                    
                                            echo '<form action="update_appt.php" method="post">
                                            <p>Patient First Name: <input type="string" name="FName" value = '. $ArrayofFName[$count] .' placeholder="First Name" required onkeypress = "return lettersOnly(event)"></p>
                                            <p>Patient Last Name: <input type="string" name="LName" value = '. $ArrayofLName[$count] .' placeholder="Last Name" required onkeypress = "return lettersOnly(event)"><p> ' .
                                            $type . ' 
                                            <div id="calendar">
                                            <p>Appointment Date: <input type="text" name="date" id="date" class="date" value = '. $ArrayofDate[$count] .' autocomplete="off" required onchange="showUser(this.value)"></p>
                                            </div>
                                            
                                            <label for="time">Appointment Time:</label>
                                            <select name="times" id="times" value= ' . $ArrayofTime[$count] . ' required>
                                            <option value = '. $ArrayofTime[$count] . ' selected> '. $ArrayofTime[$count] .'</option>
                                            <div id="times">
                                            </select>
                                            <br>
                                            <p>Location: <input type="string" name="patientLocation" value="Portsmouth" required readonly></p>
                                            <p>Patient Phone Number: <input type="tel" id= "patientPN" value = "'. $PatientPN .'" name="patientPN" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress = "return keypresshandlerNUM(event)"></p>
                                            <p>Patient ID: <input type="text" id="PatientID" name="patientID" value='.  $ArrayofIDs[$count] .' readonly></p><br>
                                            <div>
                                                <input class="btn btn-secondary" type="submit"  formaction="remove_appt.php" name="deletesubmit" value="Delete Patient">
                                                <input class="btn btn-primary" type="submit"  name="submit" value="Update Appointment">
                                            </div>
                                            </form>';
                                        }
                                        #When there is multiple appointments
                                        else if ($count > 1)
                                        {
                                            $DatesTheSame = true;
                                            $sql = "SELECT * FROM patients where PatientFNAME= \"" . $FName . "\"" ."AND PatientLNAME = \"". $LName . "\" AND PatientLOCATION = 'Portsmouth'";
                                            $CheckResult = mysqli_query($conn, $sql);

                                            #This forloop checks each date
                                            for($i = 0; $i < count($ArrayofTime)-1; $i++)
                                            {
                                                if(!$DatesTheSame)
                                                {
                                                    break;
                                                }
                                                else
                                                {
                                                    for($x = 0; $x < count($ArrayofTime)-1; $x++)
                                                    {
                                                        if($ArrayofDate[$x] == $ArrayofDate[$i])
                                                        {
                                                            $DatesTheSame = false;
                                                            break;
                                                        }
                                                    }
                                                }

                                            }

                                            #All in one date if they have sessions
                                            if($DatesTheSame)
                                            {
                                                $temp = strlen($ArrayofPN[0]);
                                                #Country Codes
                                                switch($temp)
                                                {
                                                    case 12:
                                                        $PatientPN = substr($temp, 1, 11);
                                                        break;
                                                    case 13:
                                                        $PatientPN = substr($temp, 2, 12);
                                                        break;
                                                    case 14:
                                                        $PatientPN = substr($temp, 3, 13);
                                                        break;
                                                    case 15:
                                                        $PatientPN = substr($temp, 4, 14);
                                                        break;
                                                }
                                                switch($ArrayofStatus[0])
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
                                                        
                                                #DO NOT WORRY ABOUT $DATE need to WORK ON IT
                                                #Cannot Update the time due to multiple sessions
                                                #Both button goes to a different page
                                                $time1 = date($TimeFormat, strtotime($ArrayofTime[0]));
                                                $time2 = date($TimeFormat, strtotime($ArrayofTime[count($ArrayofTime)-1]. " +30 MINUTE"));
                                                echo '<form action="remove_and_update_appt.php" method="post">
                                                <h5>To reschedule must delete and readd the schedule</h5>
                                                <p>Patient First Name: <input type="string" name="FName" value = '. $ArrayofFName[0] .' placeholder="First Name" required ></p>
                                                <p>Patient Last Name: <input type="string" name="LName" value = '. $ArrayofLName[0] .' placeholder="Last Name" required ><p> ' .
                                                $type . ' 
                                                
                                                <p>Appointment Date: <input type="text" name="date" id="date" class="date" value = '. $ArrayofDate[0]  .' autocomplete="off" required readonly></p>
                                                <p>Patient Appointment Start Time: <input type="string" name="StartTime" value = '. $time1 .' placeholder="10:00AM" required readonly><p>
                                                <p>Patient Appointment End Time: <input type="string" name="EndTime" value = '. $time2 .' placeholder="10:30AM" required readonly><p>
                                                <p>Location: <input type="string" name="patientLocation" value="Portsmouth" required readonly></p>
                                                <p>Patient Phone Number: <input type="tel" id= "patientPN" value = "'. $PatientPN .'" name="patientPN" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" readonly onkeypress = "return keypresshandlerNUM(event)"></p>
                                                <p>Patient ID: <input type="text" id="PatientID" name="patientID" value='.  $ArrayofIDs[0] .' readonly></p>
                                                <p>Type "Confirm" to delete a patient: <input type="text" id="confirmation" name ="confirmation" onkeypress = "return lettersOnly(event)"  onkeyup="DelButton(this.value)"></p>
                                                <div>
                                                    <input style="Display:none" class="btn btn-primary" type="submit" id="Readdsubmit" name="Readdsubmit" value="Delete and Re-Add Patient"><p></p>
                                                    <input style="Display:none" class="btn btn-secondary" type="submit" id="Removesubmit" name="Removesubmit" value="Remove Appointment">
                                                </div>
                                                </form>';
                                            }
                                            #Multiple Dates Cannot determine which one
                                            else
                                            {
                                                #Delete foreach later its for notes
                                                #Have to forloop to get all the correct Dates


                                                echo'
                                                <form action="NarrowSelection.php" method="post">
                                                <div class="card">
                                                <p class="DaysofWeek">Patient Appointment List</p>
                                                <p>Please narrow the appointment selection. Choose an appointment. To Edit or Delete</p>
                                                <div class="intercard_table_div">
                                                    <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
                                                        <tbody>';
                                                for($Counting = 0; $Counting <= count($ArrayofTime)-1; $Counting++)
                                                {
                                                    echo '<tr><td><input type="radio" value=' . $ArrayofIDs[$Counting] . ' name="PastPat" id=' . $ArrayofIDs[$Counting] . ' ><label for=' . 
                                                    $ArrayofIDs[$Counting] . '> '. $ArrayofFName[$Counting] . ' ' . $ArrayofLName[$Counting] . ' Appointment date: ' . $ArrayofDate[$Counting] .
                                                    ' Appointment Time: ' . $ArrayofTime[$Counting] .' </label></td></tr>';
                                                }
                                                echo '
                                                </tbody></table></div></div>
                                                <input class="btn btn-secondary" type="submit" name="Delete" value="Delete Patient">
                                                <input class="btn btn-primary" type="submit" name="Update" value="Update Appointment"></form>';
                                                #Delete will delete THAT specific appointment
                                                #Update will update that appointment date
                                            }
                                        }
                                    }
                                    else
                                    {
                                        echo '<h5>No Patient has been found!<h5>';
                                        ErrorInputs();
                                    }

                                }

                            }
                            else
                            {
                                echo '<h5>Error Incorrect Password!</h5>';
                                ErrorInputs();
                            }

                        }

                        ?>
                    <script type="text/javascript">
                    $('#calendar p').datepicker({
                        autoclose: true,
                        format: "mm-dd-yyyy",
                        inputs: $('.date')
                    });
                    </script>
                    <script>
                        var input = document.querySelector("#patientPN");
                        window.intlTelInput(input, {
                        hiddenInput: "patientPN",
                        utilsScript: "build/js/utils.js",
                        });
                    </script>

                    </div>
                    
                    <div>
                    
                    </div>
                </div>
                
        </div>
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>
