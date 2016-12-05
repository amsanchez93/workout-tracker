<?php

class workoutDB {

// single instance of self shared among all instances
    private static $instance = null;
// db connection config vars
    private $user = "ics321user";
    private $pass = "ics321userpw";
    private $dbHost = "localhost/XE";
    private $con = null;

//This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    private function __construct() {
        $this->con = oci_connect($this->user, $this->pass, $this->dbHost);
        if (!$this->con) {
            $m = oci_error();
            exit('Connect Error ' . $m['message']);
        }
    }

    public function get_athlete_id_by_name($name) {
        $query = "SELECT ID FROM athletes WHERE name = :user_bv";
        $stid = oci_parse($this->con, $query);
        
        oci_bind_by_name($stid, ':user_bv', $name);
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_ASSOC);
        if ($row)
            return $row['ID'];
        else
            return null;
    }

    public function get_workouts_by_athlete_id($athleteID) {
        $query = "SELECT id, description, workout_time FROM workouts WHERE athlete_id = :id_bv";
        $stid = oci_parse($this->con, $query);
        oci_bind_by_name($stid, ":id_bv", $athleteID);
        oci_execute($stid);
        return $stid;
    }

    public function create_athlete($name, $password) {
        $query = "INSERT INTO athletes (name, password) VALUES (:user_bv, :pwd_bv)";
        $stid = oci_parse($this->con, $query);
        oci_bind_by_name($stid, ':user_bv', $name);
        oci_bind_by_name($stid, ':pwd_bv', $password);
        oci_execute($stid);
    }

    public function verify_athletes_credentials($name, $password) {
        $query = "SELECT 1 FROM athletes WHERE name = :name_bv AND password = :pwd_bv";
        $stid = oci_parse($this->con, $query);
        oci_bind_by_name($stid, ':name_bv', $name);
        oci_bind_by_name($stid, ':pwd_bv', $password);
        oci_execute($stid);

//Because name is a unique value I only expect one row
        $row = oci_fetch_array($stid, OCI_ASSOC);
        if ($row)
            return true;
        else
            return false;
    }

    function insert_workout($athleteID, $description, $workouttime /*$Weight*/) {
        $query = "INSERT INTO workouts (athlete_id, description, workout_time) VALUES (:athlete_id_bv, :desc_bv, to_date(:workout_time_bv, 'YYYY-MM-DD'))";
        $stid = oci_parse($this->con, $query);
        oci_bind_by_name($stid, ':athlete_id_bv', $athleteID);
        oci_bind_by_name($stid, ':desc_bv', $description);
        oci_bind_by_name($stid, ':workout_time_bv', $this->format_date_for_sql($workouttime));
        //oci_bind_by_name($stid, ':weight_bv', $Weight);
        oci_execute($stid);
        oci_free_statement($stid);
    }

    function format_date_for_sql($date) {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts['year'] * 10000 + '-' + $dateParts['month'] * 100 + '-' + $dateParts['day'];
        }
    }

}

?>
