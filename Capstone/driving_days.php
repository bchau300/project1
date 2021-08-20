<!DOCTYPE html>
<html lang="en">
<head>
    <title>Driving Days</title>
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
    function showChange() {
        var Check = document.getElementById("validSchedule");
        var x = document.getElementById("submitChange");
        if (Check != null || Check != "") {
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
<body onload="showChange()">
    <!--This PHP right here is setting up all the information/data sets-->
    <?php
    #Select & Insert
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
                        <h2>Add Doctor's Driving Schedule</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    
                    <form action="driving_days.php" method="post">
                        <?php 

                        if(isset($_POST["FromDate"]) && isset($_POST["UntilDate"]))
                        {
                            $sql = "SELECT DocLocation, DateFrom, DateUntil FROM DocLoc";
                            $result = mysqli_query($conn, $sql);
                            $rowcount = mysqli_num_rows($result)-1;
                            $DateFormat = "m-d-Y";
                            $Todayget = date("m-d-Y");
                            $DocLoc = $_POST["currentLocation"];
                            $FROM = $_POST["FromDate"];
                            $UNTIL = $_POST["UntilDate"];
                            
                            $FromDate = DateTime::createFromFormat($DateFormat, $FROM)->getTimestamp(); 
                            $ToDate = DateTime::createFromFormat($DateFormat, $UNTIL)->getTimestamp();
                            $Today = DateTime::createFromFormat('m-d-Y', $Todayget)->getTimestamp();
                            $count = 0;
                            

                            if($ToDate > $Today )
                            {
                                if($FromDate < $ToDate)
                                {
                                    $sql = 'INSERT INTO DocLoc(DocLocation, DateFrom, DateUntil) values("'. $DocLoc . '","' . $FROM . '","' . $UNTIL . '")';
                                    $result = mysqli_query($conn, $sql);
                                    if($result)
                                    {
                                        echo '<h5> Success! Entry was inserted! Do you want to enter another time?</h5>
                                        <label for="currentLocation">Where you be Located at?</label>
                                        <select name="currentLocation" id="currentLocation">
                                        <option value="Portsmouth">Portsmouth</option>
                                        <option value="Buffalo">Buffalo</option></select><br><br>
                                        <p>From Date: <input type="string" name="FromDate" class="datepicker" autocomplete="off"</p>
                                        <p>Until Date: <input type="string" name="UntilDate" class="datepicker" autocomplete="off"</p><br>';
                                            
                                    }
                                    else
                                    {
                                        echo '<h5>Error! Was not able to insert into the database! Please try again!</h5>
                                        <label for="currentLocation">Where you be Located at?</label>
                                        <select name="currentLocation" id="currentLocation">
                                        <option value="Portsmouth">Portsmouth</option>
                                        <option value="Buffalo">Buffalo</option></select><br><br>
                                        <p>From Date: <input type="string" name="FromDate" class="datepicker" autocomplete="off"></p>
                                        <p>Until Date: <input type="string" name="UntilDate" class="datepicker" autocomplete="off"></p><br>';
                                    }
                                }
                                else
                                {
                                    echo '<h5>Error!! Timetable is wrong!! Please try again!</h5>
                                    <label for="currentLocation">Where you be Located at?</label>
                                    <select name="currentLocation" id="currentLocation">
                                    <option value="Portsmouth">Portsmouth</option>
                                    <option value="Buffalo">Buffalo</option></select><br><br>
                                    <p>From Date: <input type="string" name="FromDate" class="datepicker" autocomplete="off"></p>
                                    <p>Until Date: <input type="string" name="UntilDate" class="datepicker" autocomplete="off"></p><br>';
                                }
                            }
                        }
                        else
                        {
                            echo '<h5> Error!! No Dates was inputted! Please try again!</h5>
                            <label for="currentLocation">Where you be Located at?</label>
                                <select name="currentLocation" id="currentLocation">
                                <option value="Portsmouth">Portsmouth</option>
                                <option value="Buffalo">Buffalo</option></select><br><br>
                                <div id=calendar>
                            <p>From Date: <input type="text" name="date" id="date" class="date" autocomplete="off" ></p>
                            <p>Until Date: <input type="text" name="date" id="date" class="date" autocomplete="off"></p></div>';
                        }
                    ?>    
                    
                        <p>Add this current Driving timeline: <input class="btn btn-primary" type="submit" name="submitAdd" value="Add"><br></p>
                        <p>Change Driving Dates: <input class="btn btn-secondary" formaction="change_driving_days.php" type="submitChange" name="submitChange" style="display:none" value="Change Date"></p>
                    </div>
                    </form>
                    <script type="text/javascript">
                    $('#calendar p').datepicker({
                        autoclose: true,
                        format: "mm-d-yyyy",
                        inputs: $('.date')
                    });
                    </script>
                
                    </div>
                </div>
        </div>
    </div>

    <?php mysqli_close($conn);?>

</body>
</html>
