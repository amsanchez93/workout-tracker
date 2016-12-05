<?php
session_start();
if (!array_key_exists("user", $_SESSION)) {
    header('Location: index.php');
    exit;
}
require_once("Includes/db.php");
$athleteID = WorkoutDB::getInstance()->get_athlete_id_by_name($_SESSION['user']);

$workoutDescriptionIsEmpty = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("back", $_POST)) {
        header('Location: editWorkoutList.php');
        exit;
    } else
    if ($_POST['workout'] == "") {
        $workoutDescriptionIsEmpty = true;
    } else {
        WorkoutDB::getInstance()->insert_workout($athleteID, $_POST['workout'], $_POST["workoutTime"]);
        header('Location: editWorkoutList.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <!-- This keeps the time if the form isnt submitted correctly -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST")
            $workout = array("description" => $_POST['workout'],
                "workout_time" => $_POST['workoutTime']);
        else
            $workout = array("description" => "",
                "workout_time" => "")
        ?>
        <form name="editWorkout" action="editWorkout.php" method="POST">
            Your Workout: <input type="text" name="workout"  value="<?php echo $workout['description']; ?>" /><br/>
            <?php
            if ($workoutDescriptionIsEmpty)
                echo "Please enter workout<br/>";
            ?>
            How long did you workout for? <input type="text" name="workoutTime" value="<?php echo $workout['workout_time']; ?>"/><br/>
            <input type="submit" name="saveWorkout" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the Workout List"/>
        </form>
    </body>
</html>