<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checking In</title>
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


    function showUser(str) {
    if (str == "") 
    {
        document.getElementById("times").innerHTML = "";
        return;
    } 
    else 
        {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("times").innerHTML = this.responseText;
        }
        };
        xmlhttp.open("GET","getTimes.php?q="+str,true);
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

    function keypresshandlerNUM(event)
    {
         var charCode = event.keyCode;
         //numeric character range
         if (charCode > 31 && (charCode < 48 || charCode > 57))
             return false;
    }
    function HiddenFunction() {
        var x = document.getElementById("Walkin");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    </script>
    <style>
    .fakeimg {
        height: 200px;
        background: #aaa;
    }

    </style>
</head>
<body onchange='showChange()' >
    <!--This PHP right here is setting up all the information/data sets-->

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
                        <h2>Check in</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    <form action ="checkin_appt.php" method="post">
                    <div class="card">
                    
                        <p class="DaysofWeek">Patient Checkin Status</p>
                        <div class="intercard_table_div">
                            <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
                                <tbody>
                                    <?php

                                        date_default_timezone_set('EST');
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
                                        #Patient Status, 0 default meaning not here yet or checked in or there time is here yet
                                        #1 means patient checked in
                                        #2 Patient is done with the appointment
                                        #3 Patient missed the appointment
                                        $Today = date('m-d-Y');
                                        $sql = "SELECT PatientFNAME, PatientLNAME, PatientTIME, PATSTATUS FROM patients where APPDATE= \"" . $Today . "\"  where PatientLOCATION = 'Portsmouth'";
                                        $result = mysqli_query($conn, $sql);
                                        $rowcount = mysqli_num_rows($result)-1;
                                        $count = 0;
                                        if(mysqli_num_rows($result) > 0) 
                                        {
                                            // output data of each row  work on the time sorting later
                                            foreach($result as $printing)
                                            {
                                                $ArrayofTime[$count] = $printing["PatientTIME"];
                                                $ArrayofLName[$count] = $printing["PatientLNAME"];
                                                $ArrayofFName[$count] = $printing["PatientFNAME"];
                                                $ArrayofStatus[$count] = $printing["PATSTATUS"];
                                                $ArrayofIndex[$count] = $count;
                                                $count++;
                                            }

                                            $ArrayLen = count($ArrayofIndex)-1;
                                            for($sorting1 = 0; $sorting1<$ArrayLen; $sorting1++)
                                            {
                                                for($sorting = 0; $sorting<$ArrayLen-$sorting1; $sorting++)
                                                {
                                                    $Time1 = DateTime::createFromFormat('h:i A', $ArrayofTime[$sorting])->getTimestamp(); 
                                                    $Time2 = DateTime::createFromFormat('h:i A', $ArrayofTime[$sorting+1])->getTimestamp();
                                                    if($Time1 > $Time2)
                                                    {
                                                        $temp = $ArrayofIndex[$sorting+1];
                                                        #earlier time replaces the later one
                                                        $ArrayofIndex[$sorting+1] = $ArrayofIndex[$sorting];
                                                        $ArrayofIndex[$sorting] = $temp;
                                                    }

                                                    else if($Time1 == $Time2)
                                                    {
                                                        if (strncasecmp($ArrayofLName[$sorting], $ArrayofLName[$sorting+1] , 3) > 0)
                                                        {
                                                            $temp = $ArrayofIndex[$sorting+1];
                                                            #earlier time replaces the later one
                                                            $ArrayofIndex[$sorting+1] = $ArrayofIndex[$sorting];
                                                            $ArrayofIndex[$sorting] = $temp;
                                                        }
                                                    }
                                                }
                                            }

                                            for($i = 0; $i <= $rowcount; $i++)
                                            {
                                                #Print out the sort and add the status radio button
                                                switch($ArrayofStatus[$ArrayofIndex[$i]])
                                                {
                                                    case 0:
                                                        $StatusRadioButton = '
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="0" checked>
                                                        <label for="'. $i .'">Not here</label>
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="1">
                                                        <label for="'. $i .'">Checkin</label>
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="2">
                                                        <label for="'. $i .'">Done</label>';
                                                        echo '<tr><td>' .$ArrayofFName[$ArrayofIndex[$i]] . " " . $ArrayofLName[$ArrayofIndex[$i]] . " " . $ArrayofTime[$ArrayofIndex[$i]] . " Status: " . $StatusRadioButton . '</td></tr>';
                                                        break;
                                                    case 1:
                                                        $StatusRadioButton ='
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="0">
                                                        <label for="'. $i .'">Not here</label>
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="1" checked>
                                                        <label for="'. $i .'"">Checkin</label>
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="2">
                                                        <label for="'. $i .'">Done</label>';
                                                        echo '<tr><td>' .$ArrayofFName[$ArrayofIndex[$i]] . " " . $ArrayofLName[$ArrayofIndex[$i]] . " " . $ArrayofTime[$ArrayofIndex[$i]] . " Status: " . $StatusRadioButton . '</td></tr>';
                                                        break;
                                                    case 2:
                                                        $StatusRadioButton = '
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="0">
                                                        <label for="'. $i .'">Not here</label>
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="1">
                                                        <label for="'. $i .'">Checkin</label>
                                                        <input type="radio" id="'. $i .'" name="'. $i .'" value="2"checked>
                                                        <label for="'. $i .'">Done</label>';
                                                        echo '<tr><td>' .$ArrayofFName[$ArrayofIndex[$i]] . " " . $ArrayofLName[$ArrayofIndex[$i]] . " " . $ArrayofTime[$ArrayofIndex[$i]] . " Status: " . $StatusRadioButton . '</td></tr>';
                                                        break;
                                                    case 3:
                                                        break;
                                                    }
                                                }
                                            } 
                                        else
                                        {
                                            echo'<tr><td>No Appointments today!</td></tr>';
                                        }
                                        
                                        

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input class="btn btn-secondary" type="reset" name="ClearForm" id="ClearForm" value="Clear Form">
                    <input class="btn btn-primary" type="submit" name="submitAdd" id="submitAdd" value="Update Status">
                    </form>

                    
                    <form action="checkin_walkin.php" method="post">
                        <h5>Walkin Patient Information</h5>
                        <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" required onkeypress = 'return lettersOnly(event)'></p>
                        <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" required onkeypress = 'return lettersOnly(event)'><p>
                        <label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                        <option value="0">General Cleaning</option>
                        <option value="1">Dr.Lesinski</option>
                        </select>
                        <!-- Cant select saturday or sunday -->
                        <p>Appointment Date: <input type="string" name="date" class="datepicker" value=<?php echo date("m-d-Y")?> autocomplete="off" required readonly></p>
                        <p>Appointment Time:
                        <select name="patientTimes" id="patientTimes" value= <?php echo date("h:iA")?> required>
                            <option value = <?php echo date("h:iA")?> selected> <?php echo date("h:i A")?></option>
                            </select></p>
                        <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
                        <p>Patient Phone Number: <input type="tel" id= "patientPN" name="patientPN" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress = 'return keypresshandlerNUM(event)'></p>
                        <p>Password: <input type="password" name="password" required onkeypress = 'return keypresshandlerNUM(event)'><p>
                        <input class="btn btn-secondary" type="reset" name="ClearForm" id="ClearFormCheckin" value="Clear Form">
                        <input class="btn btn-primary" type="submit" name="submitAdd" id="submitAddCheckin" value="Add Patient">
                    </form>




                    <script src="build/js/intlTelInput.js"></script>
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
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>