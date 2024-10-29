<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormManager\FBRow;

interface ContainerDataRetriever{
    /**
     * @return FBRow[]
     */
    public function GetRows();

    /**
     * @return ContainerManager
     */
    public function GetContainerManager();
    public function GetHtml();

    /**
     * @return Loader
     */
    public function GetLoader();
    public function GetFieldsEntryData();

}