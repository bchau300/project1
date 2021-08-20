<!DOCTYPE html>
<html lang="en">
<head>
    <title>Updating Driving Days</title>
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
                            #update the schedule with an success or error message
                            $sql = "SELECT * FROM DocLoc";
                            $result = mysqli_query($conn, $sql);
                            $rowcount = mysqli_num_rows($result)-1;
                            $DateFormat = "m-d-Y";
                            $count = 0;
                            foreach($result as $printing)
                            {
                                $ArrayofIDs[$count] = $printing["DriverID"];
                                $ArrayofDocLoc[$count] = $printing["DocLocation"];
                                $ArrayofFrom[$count] = $printing["DateFrom"];
                                $ArrayofUntil[$count] = $printing["DateUntil"];
                                $ArrayofIndex[$count] = $count;
                                $count++;
                            }

                            $ArrayLen = count($ArrayofIndex)-1;
                            for($sorting1 = 0; $sorting1<$ArrayLen; $sorting1++)
                            {
                                for($sorting = 0; $sorting<$ArrayLen-$sorting1; $sorting++)
                                {
                                    $Time1 = DateTime::createFromFormat('m-d-Y', $ArrayofFrom[$sorting])->getTimestamp(); 
                                    $Time2 = DateTime::createFromFormat('m-d-Y', $ArrayofFrom[$sorting+1])->getTimestamp();
                                    if($Time1 > $Time2)
                                    {
                                        $temp = $ArrayofIndex[$sorting+1];
                                        #earlier time replaces the later one
                                        $ArrayofIndex[$sorting+1] = $ArrayofIndex[$sorting];
                                        $ArrayofIndex[$sorting] = $temp;
                                    }
                                }                                                
                            }

                            for($i = 0; $i <= $rowcount; $i++)
                            {
                                if($ArrayofDocLoc[$ArrayofIndex[$i]] != $_POST['Loc'.$rowcount] ||
                                $ArrayofFrom[$ArrayofIndex[$i]] != $_POST['From'.$rowcount] ||
                                $ArrayofUntil[$ArrayofIndex[$i]] != $_POST['Until'.$rowcount])
                                {
                                    $sqlupdate = "UPDATE Docloc set Doclocation = \"" . $ArrayofDocLoc[$ArrayofIndex[$i]] . "\", DateFrom = \"" . $ArrayofFrom[$ArrayofIndex[$i]] . "\", DateUntil = \"" . $ArrayofUntil[$ArrayofIndex[$i]] . "\" WHERE DriverID = " . $ArrayofIDs[$ArrayofIndex[$i]];
                                    $updeateresult = mysqli_query($conn, $sqlupdate);
                                    if(!$updeateresult)
                                    {
                                        echo '<h4>Error! This date cannot be change! From' . $_POST['From'.$rowcount] . ' until ' . $_POST['Until'.$rowcount] . '</h4>';
                                        break;
                                    }
                                }
                                
                            }

                            if($updeateresult)
                            {
                                '<h4>Sucess all changed dates are now updated!!</h4>';
                            }

                            echo '<label for="currentLocation">Where will you be Located at?</label>
                                <select name="currentLocation" id="currentLocation">
                                <option value="Portsmouth">Portsmouth</option>
                                <option value="Buffalo">Buffalo</option></select><br><br>
                                <div id=calendar>
                            <p>From Date: <input type="text" name="date" id="date" class="date" autocomplete="off"></p>
                            <p>Until Date: <input type="text" name="date" id="date" class="date" autocomplete="off" ></p></div>';



                        ?>    
                    </select>
                        <div>
                        <p><input type="submit" class="btn btn-primary" name="submitAdd" value="Add Driving Schedule"></p>
                        <input type="submit" class="btn btn-secondary" formaction="change_driving_days.php" id="submitChange" name="submitChange" value="Update Location" style="display:none">
                        <!--Change driving days goes to a different link-->
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
