<?php

class Dbh
{
    private $host = "localhost";
    private $db = "mujju";
    private $dbuser = "qasim";
    private $dbpass = "";


    protected function conn()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
        $conn = new PDO($dsn, $this->dbuser, $this->dbpass);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}

class Prods_assign extends Dbh
{
    public function getProducts($id="0")
    {
        $sqlexclude = "select prod_id from comp_prods where comp_id=:id ";
        $sql = "select id,concat(product_name,' | ',supplier) product_name 
                from products where id not in (".$sqlexclude.") 
                order by supplier,product_name ";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute(['id'=>$id]);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function getRemaining_Products($id="0")
    {
        $sqlexclude = "select prod_id from prod_sup where sup_id=:id ";
        $sql = "select id,concat(product_name,' | ',coalesce(maker,'na')) product_name 
                from products where id not in (".$sqlexclude.") 
                order by product_name ";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute(['id'=>$id]);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getRemaining_ProductsByCompany($id="0")
    {
        $sqlexclude = "select prod_id from comp_prods where comp_id=:id ";
        $sql = "select id,concat(product_name,' | ',coalesce(maker,'unbranded')) product_name 
                from products where id not in (".$sqlexclude.") 
                order by product_name ";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute(['id'=>$id]);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getProductsByCompany($comp_id="0")
    {
        $sqlexclude = "select prod_id from comp_prods where comp_id=:id ";
        $sql = "select id,concat(product_name,' | ',coalesce(maker,'unbranded')) product_name 
                from products where id in (".$sqlexclude.") 
                order by product_name ";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute(['id'=>$comp_id]);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getProductsBySupplier($supid="0")
    {
        $sql = "select p.id,p.product_name from prod_sup ps join products p
         on ps.prod_id=p.id   where sup_id=:id order by 2";
        
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute(['id'=>$supid]);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function setProduct($t_name, $t_source, $t_type, $t_sub_type, $t_cases, $t_units)
    {
        $sql = "insert into products(product_name,maker,product_type,product_sub_type,casesize,units)
                 values (?,?,?,?,?,?)";
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$t_name,$t_source,$t_type,$t_sub_type,$t_cases,$t_units]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getProductsForInventory($comp_id,$sup_id){
        $sql  = "select distinct p.id id, concat(product_name,' || ',coalesce(maker,'na'),' | ',
                    coalesce(casesize,'na'),coalesce(units,'na')) as name from products p 
                    join comp_prods cp on p.id=cp.prod_id join prod_sup ps on p.id=ps.prod_id 
                    left join inventory i on i.product_id=p.id 
                    where 
                    cp.comp_id=? and
                    ps.sup_id=?
                    order by 2
                    ";
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$comp_id,$sup_id]);
            $row = $stmt->fetchAll();
            return $row;
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public function getProductDetails($prod_id){
        $sql = "select product_name,product_type,product_sub_type,maker,casesize,units from products p 
                 where id=?";
        
        try{
            $stmt  = $this->conn()->prepare($sql);
            $stmt->execute([$prod_id]);
            $row = $stmt->fetch();
            return $row;
        }catch(PDOException $e){
            die($e->getMessage());
        }

    }

    public function getProductName($prod_id){
        $sql = "select product_name from products where id=?";
        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$prod_id]);
            $row = $stmt->fetch();
            return $row->product_name;
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

}

class Companies extends Dbh
{
    public function getAllCompanies($name="")
    {
        $name = $name."%";
        $sql = "select * from company where name like ?";
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$name]);
            $row = $stmt->fetchAll();

            return $row;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getCompanyById($id)
    {
        $sql = "select * from company where id=?";
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            return $row;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function addCompany($locname, $loctype="")
    {
        $sql = "insert into company(name) values (?)";
        $stmt = $this->conn()->prepare($sql);
        
        try {
            $stmt->execute([$locname]);
            return 1;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function setCompanyProducts($comp_id, $prodid)
    {
        $sql = "insert into comp_prods(prod_id,comp_id) values (?,?) on duplicate key update ";
        $sql .= "prod_id=?, comp_id=?";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$prodid,$comp_id,$prodid,$comp_id]);
        
            return TRUE;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return FALSE;
        }
    }
    public function getCompanyName($prod_id){
        $sql = "select name from company where id=?";
        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$prod_id]);
            $row = $stmt->fetch();
            return $row->name;
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

}

class Locations extends Dbh
{
    public function getLocations()
    {
        $sql = "select l.*,c.name comp_name from locations l left join company c on l.comp_id=c.id";

        try {
            $stmt = $this->conn()->query($sql);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getLocationsByCompanyId($comp_id)
    {
        $sql = "select * from locations l where comp_id is null or comp_id=? order by id";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$comp_id]);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function addLocation($locname, $loctype, $company=null)
    {
        $sql = "insert into locations(name,type,comp_id) values (?,?,?)";
        $stmt = $this->conn()->prepare($sql);
        
        try {
            $stmt->execute([$locname,$loctype,$company]);
            return 1;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

class Suppliers extends Dbh
{
    public function getSuppliers()
    {
        $sql = "select * from suppliers";

        try {
            $stmt = $this->conn()->query($sql);
            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function setSupplierProducts($supid, $prodid)
    {
        $sql = "insert into prod_sup(prod_id,sup_id) values (?,?) on duplicate key update ";
        $sql .= "prod_id=?, sup_id=?";

        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$prodid,$supid,$prodid,$supid]);
        
            return TRUE;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return FALSE;
        }
    }



    public function getSuppliersByName($supname)
    {
        $sql = "select * from suppliers where name like ?";
        $supname= "$supname%";
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$supname]);



            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getSupplierById($supid)
    {
        $sql = "select * from suppliers where id=?";
        
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$supid]);



            $row = $stmt->fetch();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //get supplier by Company Id
    public function getSuppliersByCompanyId($id)
    {
        // 1. we need to select products assigned to company
        // 2. then we need to select suppliers of products in 1.
        $sql1 = "select prod_id from comp_prods where comp_id=?";
        $sql2 = "select distinct s.name,s.id from suppliers s join prod_sup ps on s.id=ps.sup_id 
                    where ps.prod_id in ($sql1) order by 1";
        
        try {
            $stmt = $this->conn()->prepare($sql2);
            $stmt->execute([$id]);



            $row = $stmt->fetchAll();
        
            return $row;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function setSupplier($t_name, $t_address, $t_contact, $t_number)
    {
        $sql = "insert into suppliers(name,address,contactref,contactno)
        values (?,?,?,?)";
        try {
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$t_name,$t_address,$t_contact,$t_number]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}

class Inventory extends Dbh{
    public function addInventory($tdate,$comp_id,$receiver,$supplier,$inv_num,$acc_ref,
                                    $prodid,$qty,$namount,$tad,$discount,$taxrate,$tax,$amount,$img_ref){
        $sql = "insert into inventory(date,created_date,comp_id,sup_id,loc_id,product_id,cases,namount,tad,
                discount,taxrate,tax,amount,invoice_ref,acc_ref,invoice_img_ref) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$tdate,date("Y-m-d"),$comp_id,$supplier,$receiver,$prodid,$qty,$namount,$tad,$discount
                        ,$taxrate,$tax,$amount,$inv_num,$acc_ref,$img_ref]);
            
        } catch(PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getInvBasics($invid){
        $sql="select i.date,c.id comp_id,c.name company,s.id sup_id,s.name supplier,i.invoice_ref,l.id loc_id,l.name location,
            acc_ref,invoice_img_ref from inventory i 
            join company c on i.comp_id=c.id
            join suppliers s on i.sup_id=s.id
            join locations l on i.loc_id=l.id
            where i.id=?";
    
        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$invid]);
            $row = $stmt->fetch();

            return $row;


        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public function getInvItems($date,$comp_id,$sup_id,$loc_id,$inv_num){
        $sql = "select i.id,p.id prod_id,p.product_name,cases,namount,tad,discount,taxrate,tax,amount,acc_ref from 
        inventory i join products p on i.product_id=p.id 
        where
            date = ? and 
            comp_id=? and
            sup_id=? and
            loc_id=? and
            invoice_ref=? and
            del = 0;
            ";
        
        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$date,$comp_id,$sup_id,$loc_id,$inv_num]);
            $row = $stmt->fetchAll();

            return $row;
        }catch(PDOException $e){
            die($e->getMessage());

        }
    }

    public function updateInventory($itemid,$acc_ref,$delitem,$qty,$namount,$tad,$discount,$taxrate,$tax,$amount,$img_ref){
        $sql = "update inventory set cases=?, namount=?,tad=?,discount=?,taxrate=?,tax=?,amount=?,invoice_img_ref=?,
                del=?,acc_ref=? where id=?";
        
        try{
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$qty,$namount,$tad,$discount,$taxrate,$tax,$amount,$img_ref,$delitem,$acc_ref,$itemid]);
            return true;

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    
}





$GLOBALS['host']= "localhost";
$GLOBALS['db'] = "mujju";
$GLOBALS['dbuser'] = 'qasim';
$GLOBALS['dbpass'] = "";
