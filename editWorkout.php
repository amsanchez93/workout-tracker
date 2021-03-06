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
        WorkoutDB::getInstance()->insert_workout($athleteID, $_POST['workout'], $_POST['pounds'], $_POST["workoutTime"]);
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
                "weight" => $_POST['pounds'],
                "workout_time" => $_POST['workoutTime']);
        else
            $workout = array("description" => "",
                "weight" => "",
                "workout_time" => "")
        ?>
        <form name="editWorkout" action="editWorkout.php" method="POST" align="center">
            Your Workout: <input type="text" name="workout"  value="<?php echo $workout['description']; ?>" /><br/>
            <?php
            if ($workoutDescriptionIsEmpty)
                echo "Please enter workout<br/>";
            ?>
            What is your current weight? <input type="text" name="pounds" value="<?php echo $workout['weight']; ?>"/><br/> 
            When was your workout? <input type="text" name="workoutTime" value="<?php echo $workout['workout_time']; ?>"/><br/>
            <input type="submit" name="saveWorkout" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the Workout List"/>
        </form>
        <p style="text-align:center;">
        <img src="images/dogs.gif" title="running" alt="gif of dog running" align="center"/>
    </p>
    </body>
</html>
