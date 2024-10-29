<?php


namespace rednaoeasycalculationforms\pr;


use rednaoeasycalculationforms\ajax\EntriesAjax;
use rednaoeasycalculationforms\core\db\LinkRepository;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\FormulaCalculator;
use rednaoeasycalculationforms\DTO\FilterConditionOptionsDTO;
use rednaoeasycalculationforms\Managers\QueryManager\QueryManager;
use rednaoeasycalculationforms\pr\ajax\FormListPRAjax;
use rednaoeasycalculationforms\pr\ajax\PREntriesAjax;
use rednaoeasycalculationforms\pr\ajax\PRSettings;
use rednaoeasycalculationforms\pr\Managers\EntryLoader\EntryLoader;


class PRLoader
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
        \add_filter('rednao-calculated-fields-load-designer',array($this,'LoadDesigner'));
        \add_filter('rednaoeasycalculationforms_get_formula_calculator',array($this,'GetFormulaCalculator'),10,2);
        \add_shortcode('rnentry',array($this,'LoadEntry'));

        add_action('allinoneforms_before_entry_search',array($this,'BeforeSearch'),10,2);
        new PREntriesAjax($this->Loader);
        new PRSettings($this->Loader);

    }

    /**
     * @param $querymanager QueryManager
     * @param $condition
     * @return void
     * @throws \rednaoeasycalculationforms\core\Exception\FriendlyException
     */
    public function BeforeSearch($queryManager,$condition){
        $condition=(new FilterConditionOptionsDTO())->Merge($condition);
        if($condition!=null&&count($condition->ConditionGroups)>0)
        {
            $queryManager->AddCondition($condition);

        }
    }

    public function LoadEntry()
    {
        if(!isset($_GET['ref']))
        {
            echo "Invalid entry";
            return;
        }

        $ref=\strval($_GET['ref']);


        $linkRepository=new LinkRepository($this->Loader);
        $components=\explode('__',$ref);
        if(count($components)<2)
        {
            echo "Invalid entry";
            return;
        }

        $data=$linkRepository->GetLinkData($components[0],$components[1]);

        if($data=='')
        {
            echo 'Invalid Entry';
            return;
        }

        $entryLoader=new EntryLoader($this->Loader);
        $entryLoader->SetAllowEdition($data->LinkOptions->AllowEdition);
        $result=$entryLoader->LoadEntry($data->EntryId);
        if(!$result)
        {
            echo "Invalid Entry";
            return;
        }
        return $entryLoader->Load();

    }

    public function GetFormulaCalculator($return,$field)
    {
        return new FormulaCalculator($field);
    }

    public function BeforeAddOrderMeta($item)
    {
        if($item->Type=='fileupload')
        {
            foreach($item->Value as $fileToUpload)
            {
                $fileManager=new FileManager($this->Loader);
                $fileToUpload->Path=$fileManager->MaybeMoveToPermanentPath($fileToUpload->Path);
            }
        }

        if($item->Type=='repeater')
        {
            foreach ($item->Value as &$repeaterItem)
            {
                foreach($repeaterItem as &$field)
                {
                    $this->BeforeAddOrderMeta($field);
                }
            }
        }

        if($item->Type=='grouppanel')
        {
            foreach ($item->Value as &$repeaterItem)
            {
                $this->BeforeAddOrderMeta($repeaterItem);
            }
        }

    }

    public function LoadDesigner($dependencies)
    {
        $this->Loader->AddScript('FormulaParser','js/dist/FormulaParser_bundle.js',array('@form-builder','@products-builder'));
        $this->Loader->AddScript('DesignerPRO','js/dist/ProductDesignerPro_bundle.js',array('@form-builder','@products-builder','@FormulaParser'));

        $this->Loader->AddStyle('DesignerPRO','js/dist/ProductDesignerPro_bundle.css');

        return $dependencies;
    }


}