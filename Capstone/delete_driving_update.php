<!DOCTYPE html>
<html lang="en">
<head>
    <title>Deleted Driving Days</title>
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
        var Changecheck = document.getElementById("submitChange");
        var DeleteCheck = document.getElementById("submitDelete");
        if (Changecheck != null || Changecheck != "") {
            Changecheck.style.display = "block";
        } else {
            Changecheck.style.display = "none";
        }
        if (DeleteCheck != null || Changecheck != "") {
            DeleteCheck.style.display = "block";
        } else {
            DeleteCheck.style.display = "none";
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
    #Delete User
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

                            $sql = "SELECT * FROM DocLoc";
                            $result = mysqli_query($conn, $sql);
                            
                            $DateFormat = "m-d-Y";

                            if(mysqli_num_rows($result) > 0)
                            {
                                $rowcount = mysqli_num_rows($result)-1;
                                for($i = 0; $i < $rowcount; $i++)
                                {
                                    if(isset($_POST['Doc'.$i]))
                                    {
                                        $sqldelete = "DELETE FROM DOCLOC WHERE DriverID = " . $i;
                                        $resultdelete = mysqli_query($conn, $sqldelete);
                                        if(!$resultdelete)
                                        {
                                            echo '<h5>Error! Cannot delete this date!!</h5>';
                                            break;
                                        }
                                    }
                                }
                            }
                            if($resultdelete)
                            {
                                echo '<h5>Success! Driving date(s) was deleted!</h5>'
                            }


                        ?>    
                            <label for="currentLocation">Where will you be Located at?</label>
                                <select name="currentLocation" id="currentLocation">
                                <option value="Portsmouth">Portsmouth</option>
                                <option value="Buffalo">Buffalo</option></select><br><br>
                                <div id=calendar>
                            <p>From Date: <input type="text" name="date" id="date" class="date" autocomplete="off"></p>
                            <p>Until Date: <input type="text" name="date" id="date" class="date" autocomplete="off" ></p></div>
                    </select>
                        <div>
                        <input type="submit" class="btn btn-primary" name="submitAdd" value="Add Driving Schedule"><p></p>
                        <input type="submit" class="btn btn-secondary" formaction="change_driving_days.php" id="submitChange" name="submitChange" value="Update Location" style="display:none"><p></p>
                        <input type="submit" class="btn btn-secondary" formaction="delete_driving_days.php" id="submitDelete" name="submitDelete" value="Delete Driving Days" style="display:none">
                        </div>
                    </form>
                    <script type="text/javascript">
                    $('#calendar p').datepicker({
                        autoclose: true,
                        format: "mm-dd-yyyy",
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
