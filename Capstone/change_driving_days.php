<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Driving Days</title>
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
        if (Check != "") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
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
            <div class="col-sm-10">
                <div class="row">
                    <div class="card-group" style="padding:1%;">
                    <form action="change_driving_update.php" method="post">
                    <div class="card" style='text-align:center;'>
                    <p class="CheckinList" id="DocList">Update Doctor's location</p>
                        <div class="intercard_table_div">
                            <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
                                <tbody>
                                    <?php
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
                                        $locoutput = "";
                                        if($ArrayofDocLoc[$ArrayofIndex[$i]]  == 'Portsmouth')
                                        {
                                            $locoutput = '<label for="Loc' . $i . '">Location?</label>
                                            <select name="Loc' . $i . '" id="Loc"' . $i . '">
                                            <option value="Portsmouth" selected>Portsmouth</option>
                                            <option value="Buffalo">Buffalo</option></select><br>';
                                        }
                                        else if ($ArrayofDocLoc[$ArrayofIndex[$i]]  == 'Buffalo')
                                        {
                                            $locoutput  = '<label for="Loc"' . $i . '">Location?</label>
                                            <select name="Loc' . $i . '" id="Loc"' . $i . '">
                                            <option value="Portsmouth">Portsmouth</option>
                                            <option value="Buffalo" selected>Buffalo</option></select><br>';
                                        }
                                        echo'<tr><td>' . $locoutput . '
                                        <div id=calendar>
                                        <p>From Date: <input type="text" name="From' . $ArrayofIDs[$ArrayofIndex[$i]] . '" id="From' . $ArrayofIDs[$ArrayofIndex[$i]] . '" class="date" value="'. $ArrayofFrom[$ArrayofIndex[$i]] . '" autocomplete="off" required></p>
                                        <p>Until Date: <input type="text" name="Until' . $ArrayofIDs[$ArrayofIndex[$i]] . '" id="Until' . $ArrayofIDs[$ArrayofIndex[$i]] . '" class="date" value="'. $ArrayofUntil[$ArrayofIndex[$i]] . '" autocomplete="off" required></p></div></tr></td>';
                                        
                                    }


                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <input class="btn btn-secondary" formaction="change_driving.php" type="submit" name="submitAdd" value="Go back to Add">
                        <input class="btn btn-primary" type="submit" id="submitChange" name="submitChange" value="Change Schedule">
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
