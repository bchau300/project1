<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="home_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type = "text/JavaScript">
         <!--
            function AutoRefresh( t ) {
               setTimeout("location.reload(true);", t);
            }
         //-->
      </script>
    <style>

    .fakeimg {
        height: 200px;
        background: #aaa;
    }


    </style>
</head>
<body onload = "JavaScript:AutoRefresh(300000);">
    <!--This PHP right here is setting up all the information/data sets-->
    <?php
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
                            include $file;
                        }//opens a file and reads it
                    ?>
                </div>
            </div>
            <div class="col-sm-10" id="timetable">
                <div class="row">
                    <div class="col-sm-8">
                        <h5>Portsmouth Family Dental Weekly Schedule</h5>
                        <?php#change the day of the week?>
                        <h3>Week of <?php echo $dt->format('F d')?></h3>
                        <a class="btn btn-secondary" href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1).'&year='.$year; ?>">Prev Week</a> <!--Previous week-->
                        <a class="btn btn-primary" href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1).'&year='.$year; ?>">Next Week</a> <!--Next week-->
                    </div>
                    <div class="col-sm-4 d-flex justify-content-end flex-grow-1">
                        <a href="http://localhost/WOrk/IT493/index_2.php"><button type="button" id="button_change_Buffalo" class="btn btn-primary" style="margin:1%">Change to Lesinski Family Dental (Buffalo)</button></a>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!-- Use hidden and ids to make things visible? display:none in css disappears. -->
                    <div class="card-group" style="padding:1%;"> <!-- Each of these contains a date and a ul, containing a time and name for the appointment. Alt, contains "DRIVING DAY." Background colors to show location. -->

                        <?php
                        do {


                            echo'
                            <div class="card">
                                <p class="DaysofWeek">' . $dt->format('l') . '<br>' . $dt->format('m-d') . '</p>' . '
                                <div class="intercard_table_div">
                                    <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
                                        <tbody>';
                                        if($dt->format('l') !== 'Sunday' && $dt->format('l') !== 'Saturday')
                                        {
                                            $Stringdate = $dt->format('m-d-Y');
                                            $sql = "SELECT PatientFNAME, PatientLNAME, PatientTIME, PATSTATUS FROM patients where APPDATE= \"" . $Stringdate . "\" AND PatientLOCATION= 'Portsmouth'";
                                            $result = mysqli_query($conn, $sql);
                                            $rowcount = mysqli_num_rows($result)-1;
                                            $count = 0;

                                            $day = $dt->format('l');

                                            if($day == 'Monday')
                                            {   
                                                $currentTime = "08:00AM";
                                                $nextTime = date("h:iA", strtotime($currentTime ." +30 MINUTE"));
                                                $hoursopen = 17;
                                            }
                                            else if($day == 'Friday')
                                            {
                                                $currentTime = "08:00AM";
                                                $nextTime = date("h:iA", strtotime($currentTime ." +30 MINUTE"));
                                                $hoursopen = 8;
                                            }
                                            else
                                            {
                                                $currentTime = "07:00AM";
                                                $nextTime = date("h:iA", strtotime($currentTime ." +30 MINUTE"));
                                                $hoursopen = 19;
                                            }

                                            if(mysqli_num_rows($result) > 0) 
                                            {
                                                //
                                                foreach($result as $printing)
                                                {
                                                    $ArrayofTime[$count] = $printing["PatientTIME"];
                                                    $ArrayofLName[$count] = $printing["PatientLNAME"];
                                                    $ArrayofFName[$count] = $printing["PatientFNAME"];
                                                    $ArrayofStat[$count] = $printing["PATSTATUS"];
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
                                                    switch($ArrayofStat[$ArrayofIndex[$i]])
                                                    {
                                                        #Patient Status, 0 default meaning not here yet or checked in or there time is here yet
                                                        #1 means patient checked in
                                                        #2 Patient is done with the appointment
                                                        #3 Patient missed the appointment
                                                        #4 Patient is a walkin, should be taken care of last due to not having an appointment
                                                        case 0:
                                                            $Background = "";
                                                            break;
                                                        case 1:
                                                            $Background = "style=Background-color:#63ffa4";
                                                            break;
                                                        case 2:
                                                            $Background = "style=Background-color:#00ccff";
                                                            break;
                                                        case 3:
                                                            $Background = "style=Background-color:#ff5757";
                                                            break;
                                                        case 4:
                                                            $Background = "style=Background-color:#ffcb78";
                                                        default:
                                                            break;
                                                    }
                                                    echo "<tr " . $Background . "><td>" . $ArrayofFName[$ArrayofIndex[$i]] . " " . $ArrayofLName[$ArrayofIndex[$i]] . "  " . $ArrayofTime[$ArrayofIndex[$i]] . "</td></tr>";
                                                }
                                                unset($ArrayofTime);
                                                unset($ArrayofLName);
                                                unset($ArrayofFName);
                                                unset($ArrayofStat);
                                                unset($ArrayofIndex);

                                            } 
                                            else 
                                            {
                                                
                                                echo "<tr><td>No Appointments for this day!</td></tr>";
                                                
                                            }
                                        }
                                        else
                                        {
                                            echo "<tr><td>Day Off</td></tr> ";
                                        }
                                    echo'</tbody>
                                    </table>
                                </div>
                            </div>';
                            

                            $dt->modify('+1 day');
                            
                        } while ($week == $dt->format('W') );


                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="jumbotron text-center" style="margin-bottom:0">
    <p>Footer</p>
</div>-->
<?php mysqli_close($conn);?>
</body>
</html>
