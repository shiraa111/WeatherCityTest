<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING );
class City_model {
    private $ID = 0;
    public  $city_name = NULL;
    private $db_table = "Cities";
    private  $db;


    public function __construct($ID = 0){
        $this->db = DB::getconnection();

        if($ID !== 0){
            $sql = "SELECT * FROM $this->db_table WHERE id = $ID";
            $q = $this->db->query($sql);
            if ($q->num_rows > 0){
                $row = $q->fetch_assoc();
                $this->ID = $ID;
                $this->city_name = $row['city_name'];
            }
        }
    }

    public function save(){
        $sql = "SELECT * FROM $this->db_table WHERE city_name ='$this->city_name'";
        $q = $this->db->query($sql);
        if ($q->num_rows > 0){
            $row = $q->fetch_assoc();
            $this->ID = $row['id'];
            return $this->ID;

        } else {
            $sql = "INSERT INTO $this->db_table(`city_name`) VALUES ('$this->city_name')";
            $q = $this->db->query($sql);
            if($q){
                $this->__construct($this->db->insert_id);
                return $this->ID;
            }
            else
                return false;
        }
    }
    public function CheckCityExists(){
        $sql = "SELECT * FROM $this->db_table WHERE city_name ='$this->city_name'";
        $q = $this->db->query($sql);
        if ($q->num_rows > 0){
            $row = $q->fetch_assoc();
            $this->ID = $row['id'];
            return $this->ID;
        }
        return false;
    }



}
