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
    <link rel="stylesheet" href="date/css/bootstrap-datepicker.css">
    <script type="text/javascript" src="date/js/bootstrap-datepicker.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <script>
    function showUser(str) {
    if (str == "") {
        document.getElementById("times").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("times").innerHTML = this.responseText;
        }
        };
        xmlhttp.open("GET","getTimesChange.php?q="+str,true);
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

     function UpdateButton(str)
    {
        var Updatebutton = document.getElementById("Update");
        if(str.toLowerCase() =="confirm")
        {
            Updatebutton.style.display = "block";
        }
        else
        {
            Updatebutton.style.display = "none";
        }
    }

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
    body {
        background-color: #C6DEFF;
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
                            include $file;
                        }//opens a file and reads it
                    ?>
                </div>

            </div>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-8">
                        <h2>Change Appointment</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                        <div class="card-group" style="padding:1%;">
                        <form>
                        <h6>Current Patient Information</h6>
                            
                            <?php
                            $sql = "SELECT * FROM PATIENTS WHERE Patientid = " . $_POST["patientID"];
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $SQLID = $row["Patientid"];
                            echo '<p>Patient First Name: ' . $row["PatientFNAME"] . '</p>';
                            echo '<p>Patient Last Name: ' . $row["PatientLNAME"] . '</p>';
                            echo '<p>Patient Appointment Date: ' . $row["APPDATE"] . '</p>';
                            echo '<p>Patient Appointment Time: ' . $row["PatientTIME"] . '</p>';
                            echo '<p>Patient Location: ' . $row["PatientLOCATION"] . '</p>';
                            echo '<p>Patient Patient Phone Number: ' . $row["PatientPN"] . '</p>';
                            
                            ?>    

                            <br><br>
                        </form>
                        <form></form>
                        <form action="updating_appt.php" method="post">

                        <h6>New Patient Information</h6>
                        
                                <?php
                                    #Note: Allow changes before submitting This goes to another page to update it

                                    $TimeFormat = "h:i A";

                                    $PatientTime = date($TimeFormat, strtotime($_POST["times"]));
                                    echo '<p>Patient First Name: <input type="string" name="FName" value = '. $_POST["FName"].' placeholder="First Name" required></p>';
                                    echo '<p>Patient Last Name: <input type="string" name="LName" value = '. $_POST["LName"].' placeholder="Last Name" required></p>';
                                    echo '<label for="Appt_Type">Appointment Type: </label> <select name="Appt_Type" id="Appt_Type" value = ' . $_POST["Appt_Type"] . ' required> 
                                    <option value="0">General Cleaning</option>
                                    <option value="1">Dr.Lesinski</option>
                                    </select>';
                                    echo '<div id="calendar">
                                    <p>Appointment Date: <input type="text" name="date" id="date" class="date" value = '. $_POST["date"] . ' placeholder="05-25-2021" autocomplete="off" required onchange="showUser(this.value)"></p>
                                    </div>';
                                    echo '<label for="time">Appointment Time:</label>
                                    <select name="times" id="times" value= ' . $PatientTime . ' required>
                                    <option value = '. $PatientTime . ' selected> '. $PatientTime .'</option>
                                    <div id="times">
                                    </select>
                                    </select>
                                    <br>';
                                    echo '<p>Patient Location: <input type="string" name="patientLocation" value = '. $_POST["patientLocation"].' placeholder="Portsmouth" required readonly></p>';
                                    echo '<p>Patient Phone Number: <input type="string" name="patientPN" value = "'. $_POST["patientPN"].'" placeholder="+17574852222" ></p>';
                                    echo '<p>Patient ID: <input type="string" name="PatientID" id="PatientID" value = '. $_POST["patientID"].' readonly ></p>';
                                    echo '<input type="hidden" name="SQLID" id="SQLID" value ='. $SQLID .'>';

                                ?>
                                <p>Type "Confirm" to update patient: <input type="string" id="confirmation" name="confirmation" onkeypress = "return lettersOnly(event)" onkeyup="UpdateButton(this.value)" required>
                                <div>
                                    <input class="btn btn-secondary" id="Search" type="submit" name="Search" formaction="search_appt.php" value="Back to Search"><p></p>
                                    <input class="btn btn-primary" style="Display:none" id="Update" type="submit" name="submit" value="Update Patient">
                                </div>

                        </form>
                    </div>
                    <!--  -->

                    <div class="card-group" style="padding:%;">
                    </div>
                    
                    <div>
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
                </div>
                
        </div>
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>
