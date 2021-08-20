                    <div class="intercard_table_div">
                        <table id="intercard_table_table" class="table table-sm table-bordered table-striped">
                        <h5>Dr.Lesinski's Schedule:</h5>
                        <?php
                            #This file only purpose is to get all the information for the driving days
                            #Select User Account
                            $host = "127.0.0.1";
                            $user = "root";
                            $password = "test123";
                            $database = "test";
                            // Create connection
                            $conn = mysqli_connect($host, $user, $password, $database);
                            $sql = "SELECT * FROM DOCLOC";
                            $result = mysqli_query($conn, $sql);
                            $rowcount = mysqli_num_rows($result)-1;
                            $count = 0;
                            $TodaysDate = date('m-d-Y');
                            foreach($result as $printing)
                            {
                                $ArrayofFROM[$count] = $printing["DateFrom"];
                                $ArrayofUntil[$count] = $printing["DateUntil"];
                                $ArrayofLoc[$count] = $printing["DocLocation"];
                                $ArrayofIndexes[$count] = $count;
                                $count++;
                            }
                            if(mysqli_num_rows($result) > 1) 
                            {

                                $ArrayLen = count($ArrayofIndexes)-1;
                                for($sorting1st = 0; $sorting1st<$ArrayLen; $sorting1st++)
                                {
                                    for($sortingD = 0; $sortingD<$ArrayLen-$sorting1st; $sortingD++)
                                    {
                                        $Time1 = DateTime::createFromFormat('m-d-Y', $ArrayofFROM[$sortingD])->getTimestamp(); 
                                        $Time2 = DateTime::createFromFormat('m-d-Y', $ArrayofFROM[$sortingD+1])->getTimestamp();
                                        if($Time1 > $Time2)
                                        {
                                            $temp = $ArrayofIndexes[$sortingD+1];
                                            #earlier time replaces the later one
                                            $ArrayofIndexes[$sortingD+1] = $ArrayofIndexes[$sortingD];
                                            $ArrayofIndexes[$sortingD] = $temp;
                                        }
                                    }
                                    
                
                                }
                                ##$ArrayofIndexes[$i]
                                for($i = 0; $i <= $rowcount; $i++)
                                {
                                    $Time1 = DateTime::createFromFormat('m-d-Y', $TodaysDate)->getTimestamp(); 
                                    $Time2 = DateTime::createFromFormat('m-d-Y', $ArrayofUntil[$ArrayofIndexes[$i]])->getTimestamp();
                                    $Time3 = DateTime::createFromFormat('m-d-Y', $ArrayofFROM[$ArrayofIndexes[$i]])->getTimestamp();

                                    if($Time3 <= $Time1 && $Time2 >= $Time1)
                                    {
                                        echo '<tr style="Background-color:#56e37c;"><td id="validSchedule" >' . 'Location: ' . $ArrayofLoc[$ArrayofIndexes[$i]] . '<br>From: ' . substr($ArrayofFROM[$ArrayofIndexes[$i]], 0, 5) . ' Until: ' . substr($ArrayofUntil[$ArrayofIndexes[$i]], 0, 5) . '</td></tr>';
                                    }
                                    else
                                    {
                                        echo '<tr><td id="validSchedule">' . 'Location: ' . $ArrayofLoc[$ArrayofIndexes[$i]] . '<br>From: ' . substr($ArrayofFROM[$ArrayofIndexes[$i]], 0, 5) . ' Until: ' . substr($ArrayofUntil[$ArrayofIndexes[$i]], 0, 5) . '</td></tr>';
                                    }
                               }       

                            }
                            else if (mysqli_num_rows($result) == 1)
                            {
                                echo '<tr><td>id="validSchedule"' . 'Location: ' . $ArrayofLoc[0] . '<br>From: ' . substr($ArrayofFROM[0], 0, 5) . ' Until: ' . substr($ArrayofTO[0], 0, 5) . '</td></tr>';
                            }
                            else
                            {
                                echo '<h5>No Driving Days in the Schedule!</h5>';
                            }

                            date_default_timezone_set('EST');
                        ?>
                        </table>
                    </div>