<?php
session_start();
if (array_key_exists("user", $_SESSION)) {
    echo "Hello " . $_SESSION['user'] . "!" . "<br>" . "Here is your workout list:";
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <table border="black">
            <tr><th>Workout</th><th>Workout Date</th><th>Weight</th></tr>
    <?php
    require_once("Includes/db.php");
    $athleteID = WorkoutDB::getInstance()->get_athlete_id_by_name($_SESSION["user"]);
    $stid = WorkoutDB::getInstance()->get_workouts_by_athlete_id($athleteID);
    while ($row = oci_fetch_array($stid)) {
        echo "<tr><td>" . htmlentities($row['DESCRIPTION']) . "</td>";

        echo "<td>" . htmlentities($row['WORKOUT_TIME']) . "</td>";
        echo "<td>" . htmlentities($row["WEIGHT"]) . "</td></tr>\n";
    }
    ?>
</table>

        <form name="addNewWorkout" action="editWorkout.php">   
            <input type="submit" value="Add New Workout"/>
        </form>
        <form name="backToMainPage" action="index.php">
            <input type="submit" value="Back To Main Page"/>
        </form>
            <img src="images/tone_it_up.png" width=512px height=213 title="tone it up" alt="message saying tone it up" />
    </body>
</html>