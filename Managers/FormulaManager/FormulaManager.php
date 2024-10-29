<?php


namespace rednaoeasycalculationforms\Managers\FormulaManager;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;

class FormulaManager
{
    /**
     * @param $field FBFieldBase
     * @param $formulaName
     */
    public static function GetFormula($field,$formulaName)
    {

        if(isset($field->Options->Formulas))
        {
            foreach ($field->Options->Formulas as $currentFormula)
            {
                if ($currentFormula->Name == $formulaName)
                    return $currentFormula;
            }
        }

        return null;

    }
}