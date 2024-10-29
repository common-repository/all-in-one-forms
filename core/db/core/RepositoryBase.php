<?php


namespace rednaoeasycalculationforms\core\db\core;


use rednaoeasycalculationforms\core\Loader;

abstract class RepositoryBase
{
    /** @var Loader */
    public $Loader;

    /** @var DBManager */
    public $DBManager;

    public function __construct($loader=null)
    {
        $this->Loader=$loader==null?AllInOneForms()->GetLoader():$loader;
        $this->DBManager=new DBManager();
    }

}