<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
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
                        <h2>Admin Page</h2>
                    </div>
                </div>
                <!--<div class="fakeimg">Fake Image</div>-->
                <div class="row">
                    </div>
                    <form action="admin_change.php" method="post">
                        <p>Master Password: <input type="password" name="Pass" required></p>
                        <br><br>
                        <p>Change Portsmouth Password: <input type="password" name="PPass"  onkeypress = 'return keypresshandlerNUM(event)'><p>
                        <p>Change Buffalo Password: <input type="password" name="BPass" onkeypress = 'return keypresshandlerNUM(event)'></p>
                       <div>
                            <input class="btn btn-secondary" type="reset" name="ClearForm" id="ClearForm" value="Clear Form">
                            <input class="btn btn-primary" type="submit" name="submitAdd" id="submitAdd" value="Change Password">
                        </div>
                    </form>

                </div>
        </div>
    </div>


    <?php mysqli_close($conn);?>
</body>
</html>
