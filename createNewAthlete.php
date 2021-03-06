<?php
require_once("Includes/db.php");

/** other variables */
$userNameIsUnique = true;
$passwordIsValid = true;
$userIsEmpty = false;
$passwordIsEmpty = false;
$password2IsEmpty = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['user'] == "")
        $userIsEmpty = true;
    $athleteID = workoutDB::getInstance()->get_athlete_id_by_name($_POST["user"]);
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (array_key_exists("back", $_POST)) {
            header('Location: index.php');
            exit;
        }
    }
    if ($athleteID) {
        $userNameIsUnique = false;
    }
    //Check for the existence and validity of the password
    if ($_POST['password'] == "")
        $passwordIsEmpty = true;
    if ($_POST['password2'] == "")
        $password2IsEmpty = true;
    if ($_POST['password'] != $_POST['password2']) {
        $passwordIsValid = false;
    }

    //If everything is OK, add the new user name and password to the database
    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) {
        workoutDB::getInstance()->create_athlete($_POST["user"], $_POST["password"]);
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header('Location: editWorkoutList.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <div style="text-align:center;">
            <h1>Welcome!</h1>
            <img src="images/pain.jpg" width=700px height=387px title="tone it up" alt="message saying no pain no gain"/>
            <br />
            <br />
        <form action="createNewAthlete.php" method="POST">
            Your name: <input type="text" name="user"/><br/>
            <?php
            if ($userIsEmpty) {
                echo ("Enter your name, please!");
                echo ("<br/>");
            }
            if (!$userNameIsUnique) {
                echo ("The person already exists. Please check the spelling and try again");
                echo ("<br/>");
            }
            ?> 
            <br />
            Password: <input type="password" name="password"/><br/>
            <?php
            if ($passwordIsEmpty) {
                echo ("Enter the password, please!");
                echo ("<br/>");
            }
            ?>
            <br />
            Confirm: <input type="password" name="password2"/><br/>
            <?php
            if ($password2IsEmpty) {
                echo ("Confirm your password, please");
                echo ("<br/>");
            }
            if (!$password2IsEmpty && !$passwordIsValid) {
                echo ("The passwords do not match!");
                echo ("<br/>");
            }
            ?>
            <br />
            <input type="submit" value="Register"/>
            <input type="submit" name="back" value="Back"/>
        </form>
    </body>
</html>
