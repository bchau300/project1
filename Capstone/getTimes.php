<?php
    #Select User Account
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

    $date = $_GET['date'];
    $Type = $_GET['type'];
    
    $sql = "SELECT PatientTime, HYGIENIST FROM patients WHERE APPDATE = \"" . $date . "\" and PatientLOCATION = 'Portsmouth'";

    $result = mysqli_query($conn, $sql);
    $rowcount = mysqli_num_rows($result)-1;
    $ArrayofTime = array();
    $temp = str_replace("-","/",$date);
    $day = date('l', strtotime($temp));
    $count = 0;

    
    #0 = Hygienist
    #1 = Dr lensiki
    foreach($result as $printing)
    {
        $ArrayofTime[$count] = date('h:iA', strtotime($printing["PatientTime"]));
        $ArrayofDoc[$count] = $printing["HYGIENIST"];
        $count++;
    }

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


    #Choose a time slot that is open
    echo '<select name="patientTimes" id="patientTimes" required>';
    for($i = 0; $i < $hoursopen; $i++)
    {
        $valid = true;
        if($rowcount > 0)
        {
            for($x = 0; $x < count($ArrayofTime); $x++)
            {
                if($currentTime==$ArrayofTime[$x])
                {
                    if($Type==$ArrayofDoc[$x])
                    {
                        $valid = false;
                        break;
                    }
                }
            }
        }
        if($valid)
        {
            echo "<option value=" . $currentTime .">" . $currentTime . "</option>";
        }
        $currentTime = $nextTime;
        $nextTime = date("h:iA", strtotime($currentTime ." +30 MINUTE"));
    }
    echo '</select>';
    
    
?>