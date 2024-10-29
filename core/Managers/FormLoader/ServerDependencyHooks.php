<?php
add_filter('allinoneforms_get_server_dependency_Chained','allinoneforms_load_csv',10,2);
function allinoneforms_load_csv($options,$dependency)
{
    $formId=\rednaoeasycalculationforms\Utilities\Sanitizer::GetStringValueFromPath($options,['FormId']);
    $fieldId=\rednaoeasycalculationforms\Utilities\Sanitizer::GetStringValueFromPath($dependency,['Id']);

    if($formId==''||$fieldId=='')
        return $options;


    $fileManager=new \rednaoeasycalculationforms\core\Managers\FileManager\FileManager((AllInOneForms())->GetLoader());
    $content=$fileManager->GetAssetString($formId,$fieldId.'.json');
    if($content===null)
        return $options;

    $content=json_decode($content,true);
    if($content===null)
        return $options;

    if(!isset($options['Extra']['CSV']))
        $options['Extra']['CSV']=[];

    $items=[];
    foreach($content as $key=>$value)
    {
        $currentItem=[
            "L"=>$key,

        ];

        if(isset($value['Price']))
            $currentItem['P']=$value['Price'];
        $items[]=$currentItem;
    }

    usort($items, function ($a, $b) {
        return strcmp($a['L'], $b['L']);
    });
    $options['Extra']['CSV'][$fieldId]['Items']=$items;
    $options['Extra']['CSV'][$fieldId]['Nonce']=wp_create_nonce('allinoneforms_csv_'.$formId.'_'.$fieldId);

    return $options;

}