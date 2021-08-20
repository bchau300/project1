<!DOCTYPE html>
<html lang="en">
<head>
    <title>Remove Appointment</title>
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
            xmlhttp.open("GET","getTimes.php?date="+str+"&type="+str1,true);
            xmlhttp.send();
        }

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
        var x = document.getElementById("Sessions");
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
                        <h2>Delete An Appointment</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    
                    <?php

                        $sql = "DELETE FROM patients where PatientFNAME = \"" . $_POST["FName"] . "\" AND PatientLNAME = \"" . $_POST["LName"] . "\" AND APPDATE = \"" . $_POST["date"] . "\"";
                        $result = mysqli_query($conn, $sql);
                        if($result)
                        {
                            if(isset($_POST["Readdsubmit"]))
                            {
                                echo '<form action="insert_appointment.php" method="post">
                                <h5>Success! ' . $_POST["FName"] . ' ' . $_POST["LName"] . ' has been removed! Please Add the patient on a different Date!</h5>
                                <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" value= "' . $_POST["FName"] . '" required onkeypress = "return lettersOnly(event)"></p>
                                <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" value= "' . $_POST["LName"] . '" required onkeypress = "return lettersOnly(event)"><p>
                                <label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                                <option value="0">General Cleaning</option>
                                <option value="1">Dr.Lesinski</option>
                                </select>
                                <!-- Cant select saturday or sunday -->
                                <div id="calendar">
                                    <p>Appointment Date: <input type="text" name="date" id="date" class="date" autocomplete="off" required onchange="showUser(this.value)"></p>
                                </div>
                                <p>Appointment Time: <div id="times"></div></p>
                                <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
                                <p>Patient Phone Number: <input type="tel" id= "patientPN" name="patientPN" value= "' . $_POST["patientPN"] . '" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress = "return keypresshandlerNUM(event)"></p>
                                <p>Does this Patient need multiple time slot? <input type="checkbox" value="Yes" id="timeslots" name="timeslots" onclick="HiddenFunction()" checked></p>
                                <div id="Sessions" style="display:block">
                                    <p>Add more session. Each session is 30 mins <input type="number"  id="sessions" name="sessions" palceholder="1" min="0" max="9"></p>
                                </div>
                                <br>
                                <div>
                                    <input class="btn btn-secondary" type="reset" name="ClearForm" id="ClearForm" value="Clear Form">
                                    <input class="btn btn-primary" type="submit" name="submitAdd" id="submitAdd" value="Add Patient">
                                </div>
                            </form>';
                            }
                            else
                            {
                                echo '<form action="insert_appointment.php" method="post">
                                <h5>Success! ' . $_POST["FName"] . ' ' . $_POST["LName"] . ' has been removed! Please Re-Add on a different Date!</h5>
                                <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" required onkeypress = "return lettersOnly(event)"></p>
                                <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" required onkeypress = "return lettersOnly(event)"><p>
                                <label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" required> 
                                <option value="0">General Cleaning</option>
                                <option value="1">Dr.Lesinski</option>
                                </select>
                                <!-- Cant select saturday or sunday -->
                                <div id="calendar">
                                    <p>Appointment Date: <input type="text" name="date" id="date" class="date" autocomplete="off" required onchange="showUser(this.value)"></p>
                                </div>
                                <p>Appointment Time: <div id="times"></div></p>
                                <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
                                <p>Patient Phone Number: <input type="tel" id= "patientPN" name="patientPN" placeholder="Patient Phone Number" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress = "return keypresshandlerNUM(event)"></p>
                                <p>Does this Patient need multiple time slot? <input type="checkbox" value="Yes" id="timeslots" name="timeslots" onclick="HiddenFunction()"></p>
                                <div id="Sessions" style="display:none">
                                    <p>Add more session. Each session is 30 mins <input type="number"  id="sessions" name="sessions" palceholder="1" min="0" max="9"></p>
                                </div>
                                <br>
                                <div>
                                    <input class="btn btn-secondary" type="reset" name="ClearForm" id="ClearForm" value="Clear Form">
                                    <input class="btn btn-primary" type="submit" name="submitAdd" id="submitAdd" value="Add Patient">
                                </div>
                            </form>';
                            }

                        }
                        #This should not be hit at all
                        else
                        {
                            echo '<h5>Error! Incorrect Value Please try again! The whole process again!</h5>';
                        }

                    ?>

                    




                    <script src="build/js/intlTelInput.js"></script>
                    <script type="text/javascript">
                    $('#calendar p').datepicker({
                        daysOfWeekDisabled: "0,6",
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
