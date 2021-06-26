<?php 

class Entity
{
    public $ID;
    public $P;
    public $dP;
    public $ddP;
    public $Health;
    public $Damage; 
}

// To be honest, I didn't quite understand what so different about DataMapper and ActiveRecord...
// I guess, since we probably have one mapper, we can now store our database handler here :thinking: 
class EntityMapper
{
    private $DB;

    function __construct() 
    {
        try
        {
            $this->$DB = new PDO('mysql:host=localhost;dbname=data_mapper', 'alex', 'password');
        }
        catch (PDOException $e)
        {
            printf("ERROR: %s", $e->getMessage());
            die();
        }    
    }

    public function Save(Entity $Entity) : bool
    {
        $Statement = $this->$DB->prepare("Insert Into Entities(ID, P, dP, ddP, Health, Damage) Values (?, ?, ?, ?, ?, ?)");
        $Result = $Statement->execute(array($Entity->ID, $Entity->P, $Entity->dP, $Entity->ddP, $Entity->Health, $Entity->Damage));
        return $Result;
    }

    public function Remove(Entity $Entity) : bool
    {
        $Statement = $this->$DB->prepare("Delete From Entities Where ID = ?, P = ?, dP = ?, ddP = ?, Health = ?, Damage = ?)");
        $Result = $Statement->execute(array($Entity->ID, $Entity->P, $Entity->dP, $Entity->ddP, $Entity->Health, $Entity->Damage));
        return $Result;
    }

    public function GetById($ID) : Entity
    {
        $Statement = $this->$DB->prepare("Select * From Entities Where ID = ?");
        $Entity = $Statement->execute(array($ID));
        $Result = new Entity;
        if(!empty($Entity))
        {
            $Result->ID     = $Entity["ID"];
            $Result->P      = $Entity["P"];
            $Result->dP     = $Entity["dP"];
            $Result->ddP    = $Entity["ddP"];
            $Result->Health = $Entity["Health"];
            $Result->Damage = $Entity["Damage"];
        }
        return $Result;
    }

    public function GetAll() : array
    {
        $Result = array();
        foreach($this->$DB->query('Select * From Entities') as $Entity)
        {
            $ToBePushed = new Entity;
            $ToBePushed->ID     = $Entity["ID"];
            $ToBePushed->P      = $Entity["P"];
            $ToBePushed->dP     = $Entity["dP"];
            $ToBePushed->ddP    = $Entity["ddP"];
            $ToBePushed->Health = $Entity["Health"];
            $ToBePushed->Damage = $Entity["Damage"];
            array_push($Result, $ToBePushed);
        }
        return $Result;
    }

    public function GetByDamage($Damage) : array
    {
        $Result = array();
        $Statement = $this->$DB->prepare("Select * From Entities Where Damage = ?");
        foreach($Statement->execute(array($Damage)) as $Entity)
        {
            $ToBePushed = new Entity;
            $ToBePushed->ID     = $Entity["ID"];
            $ToBePushed->P      = $Entity["P"];
            $ToBePushed->dP     = $Entity["dP"];
            $ToBePushed->ddP    = $Entity["ddP"];
            $ToBePushed->Health = $Entity["Health"];
            $ToBePushed->Damage = $Entity["Damage"];
            array_push($Result, $ToBePushed);
        }
        return $Result;
    }
}

    // Use case examples
    $Mapper = new EntityMapper();

    $Entity = new Entity();
    $Entity->ID = 1;
    $Entity->P = 15.32;
    $Entity->dP = -2.3;
    $Entity->ddP = -9.81;
    $Entity->Health = 135;
    $Entity->Damage = 12;
    $Mapper->Save($Entity);

    
    $EntityArray = $Mapper->GetAll();
    var_dump($EntityArray);

?>