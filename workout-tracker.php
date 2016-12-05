

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>workout tracker</title>
    </head>
    <body>
        <div><a href="editWorkoutList.php">Add New workout</a></div>
        <div><a href="index.php">Return</a></div>
        workout List of <?php echo htmlentities($_GET["user"]) . "<br/>"; ?> 
        <?php
        require_once("Includes/db.php");
        $athleteID = workoutDB::getInstance()->get_athlete_id_by_name($_GET["user"]);
        if (!athlete_ID) {
            exit("The person " . $_GET["user"] . " is not found. Please check the spelling and try again");
        }
        ?>
        <table border="black">
            <tr>
                <th>workout</th>
                <th>Time</th>
            </tr>
            <?php
            $stid = workoutDB::getInstance()->get_workouts_by_athlete_id(athlete_ID);  
            while ($row = oci_fetch_array($stid)) {
                echo "<tr><td>" . htmlentities($row["DESCRIPTION"]) . "</td>";
                echo "<td>" . htmlentities($row["workout_date"]) . "</td></tr>\n";
            }
            oci_free_statement($stid);
            ?>
        </table> 
    </body>
</html>
