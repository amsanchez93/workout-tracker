
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <p style="text-align:center;">
            <img src="images/tone_it_up.png" width=512px height=213 title="tone it up" alt="message saying tone it up" align="center"/></p>
        <div text align="middle">
            <?php
            session_start();
            if (array_key_exists("user", $_SESSION)) {
                echo "Hello " . $_SESSION['user'] . "!" . "<br>" . "Here is your workout list:";
            } else {
                header('Location: index.php');
                exit;
            }
            ?></div>
       
        <table border="black" align="center">
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
        <br />
        <div>
            <table border="black" align="center">
                <tr>
                    <th>Lightest Weight</th> 
                </tr>
                <?php
                $stid = workoutDB::getInstance()->get_smallest_weight_by_athlete_id($athleteID);
                while ($row = oci_fetch_array($stid)) {
                    echo "<tr><td>" . htmlentities($row["TINY"]) . "</td></tr>\n";
                }
                oci_free_statement($stid);
                ?>
            </table>
        </div>
        <br />
        <div>
            <table border="black" align="center">
                <tr>
                    <th>Heaviest Weight</th> 
                </tr>
                <?php
                $stid = workoutDB::getInstance()->get_largest_weight_by_athlete_id($athleteID);
                while ($row = oci_fetch_array($stid)) {
                    echo "<tr><td>" . htmlentities($row["LARGE"]) . "</td></tr>\n";
                }
                oci_free_statement($stid)
                ?>
            </table>
            <br />
        </div>

        <form name="addNewWorkout" action="editWorkout.php" align="center">   
            <input type="submit" value="Add New Workout"/>
        </form>
        <form name="backToMainPage" action="index.php" align="center">
            <input type="submit" value="Back To Main Page"/>
        </form>
        <p style="text-align:center;">
        <img src="images/sloth.gif" alt="Sloth Gif" align="center"></p>
    </body>
</html>