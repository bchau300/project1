<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="home_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <script>
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
    </style>
</head>
<body>
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
                        <h2>Look up</h2>
                        <h5>To look for a patient type in the <br>First and Last Name of the patient.</h5>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    <!--  -->
                    <div class="card-group" style="padding:1%;">
                    
                    <form action="menu_appt.php" method="post">
                        <p>Patient First Name: <input type="string" name="FName" placeholder="First Name" required></p>
                        <p>Patient Last Name: <input type="string" name="LName" placeholder="Last Name" required ><p>
                        <p>Location: <input type="string" name="patientLocation" value="Portsmouth" placeholder="Location" required readonly></p>
                        <p>Looking for past appointment? <input type="checkbox" id="pastAppt" name="pastAppt" value="Yes"></p>
                        <p>Password: <input type="password" name="password" required onkeypress = 'return keypresshandlerNUM(event)'><p>
                        <div>
                            <input class="btn btn-secondary" id="ClearForm" type="reset" name="reset" value="Clear Form">
                            <input class="btn btn-primary" type="submit" name="submit" value="Search Patient">
                        </div>
                    </form>
                    </div>
                    
                    <div>
                    <script src="build/js/intlTelInput.js"></script>
                    <script>
                        var input = document.querySelector("#patientPN");
                        window.intlTelInput(input, {
                        hiddenInput: "patientPN",
                        utilsScript: "build/js/utils.js",
                        });
                    </script>
                    </div>
                </div>
                <?php mysqli_close($conn);?>
        </div>
    </div>



</body>
</html>
