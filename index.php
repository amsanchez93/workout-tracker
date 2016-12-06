<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require_once("Includes/db.php");
$logonSuccess = false;


// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (workoutDB::getInstance()->verify_athletes_credentials($_POST['user'], $_POST['userpassword']));
    if ($logonSuccess == true) {
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header('Location: editWorkoutList.php');
        exit;
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <img src="images/runner.jpg" title="runner picture" alt="Picture of a Runner" />
        <h1>Workout Tracker!</h1>
        <h2>Please log in</h2>
         <form name="workout-tracker" method="GET" action="workout-tracker.php">
            Show workout list of: <input type="text" name="user" value="" />
            <input type="submit" value="Go" /> 
        </form> 
        <form name="logon" action="index.php" method="POST" >
            Username: <input type="text" name="user">
            Password:  <input type="password" name="userpassword">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!$logonSuccess)
                    echo "Invalid name and/or password";
            }
            ?>
            <input type="submit" value="Edit My workouts">
            <br>Not a member? <a href="createNewAthlete.php">Create an account!</a>
        </form>
    </body>
</html>
