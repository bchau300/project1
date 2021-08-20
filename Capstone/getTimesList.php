<?php
    #Select User Portsmouth
    $host = "127.0.0.1";
    $user = "root";
    $password = "test123";
    $database = "test";
    $dt = new DateTime;
    if (isset($_GET['year']) && isset($_GET['week'])) {
        $dt->setISODate($_GET['year'], $_GET['week']);
    } else {
        $dt->setISODate($dt->format('o'), $dt->format('W'));
    }
    $Stringdate = $dt->format('m-d-Y');
    $year = $dt->format('o');
    $week = $dt->format('W');
    // Create connection
    $conn = mysqli_connect($host, $user, $password, $database);
    // Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

    $q = $_GET['q'];
    
    $sql = "SELECT PatientTime FROM patients WHERE APPDATE = \"" . $q . "\" and PatientLOCATION = 'Portsmouth'";

    $result = mysqli_query($conn, $sql);
    $rowcount = mysqli_num_rows($result)-1;
    $ArrayofTime = array();
    $temp = str_replace("-","/",$q);
    $day = date('l', strtotime($temp));
    $count = 0;

    foreach($result as $printing)
    {
        $ArrayofTime[$count] = $printing["PatientTime"];
        $ArrayofIndex[$count] = $count;
        $count++;
    }

    if($day == 'Monday')
    {   
        $currentTime = "08:00 AM";
        $nextTime = date("h:i A", strtotime($currentTime ." +30 MINUTE"));
        $hoursopen = 17;
    }
    else if($day == 'Friday')
    {
        $currentTime = "08:00 AM";
        $nextTime = date("h:i A", strtotime($currentTime ." +30 MINUTE"));
        $hoursopen = 8;
    }
    else
    {
        $currentTime = "07:00 AM";
        $nextTime = date("h:i A", strtotime($currentTime ." +30 MINUTE"));
        $hoursopen = 19;
    }

    
    #Choose a time slot that is open
    #"<tr><td>" . $printing["PatientFNAME"] . " " . $printing["PatientLNAME"] . "  " . $printing["PatientTIME"] . "</td></tr>"
    echo '<div class="card">
    <div class="intercard_table_div">
    <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
    <tbody>';
    for($i = 0; $i < $hoursopen; $i++)
    {
        $valid = true;
        if($rowcount > 0)
        {
            for($x = 0; $x < count($ArrayofTime); $x++)
            {
                if($currentTime==$ArrayofTime[$x])
                {
                    $valid = false;
                    break;
                }
            }
        }
        if($valid)
        {
            echo "<tr><td>". $currentTime. "</td></tr>";
        }
        $currentTime = $nextTime;
        $nextTime = date("h:i A", strtotime($currentTime ." +30 MINUTE"));
    }
    echo '</div></div></table></tbody>';

    
?>