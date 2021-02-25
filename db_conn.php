<?php 

Class Dbh {
    private $host = "localhost";
    private $db = "mujju";
    private $dbuser = "qasim";
    private $dbpass = "";


    protected function conn(){

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
        $conn = new PDO($dsn, $this->dbuser, $this->dbpass);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }


}

Class Prods_assign extends Dbh{

    public function getProducts($id="0"){
        $sqlexclude = "select prod_id from comp_prods where comp_id=:id ";
        $sql = "select id,concat(product_name,' | ',supplier) product_name 
                from products where id not in (".$sqlexclude.") 
                order by supplier,product_name ";

        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute(['id'=>$id]);
            $row = $stmt->fetchAll();
        
            return $row;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    
}


Class Locations extends Dbh {

    public function getLocations(){
        $sql = "select * from locations";

        try{
            $stmt = $this->conn()->query($sql);
            $row = $stmt->fetchAll();
        
            return $row;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function addLocation($locname,$loctype){
        $sql = "insert into locations(name,type) values (?,?)";
        $stmt = $this->conn()->prepare($sql);
        
        try{
            $stmt->execute([$locname,$loctype]);
            return 1;
            
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}

$GLOBALS['host']= "localhost";
$GLOBALS['db'] = "mujju";
$GLOBALS['dbuser'] = 'qasim';
$GLOBALS['dbpass'] = "";

?>