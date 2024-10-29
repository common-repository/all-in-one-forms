<?php

namespace rednaoeasycalculationforms\PublicApi;

use rednaoeasycalculationforms\core\db\EntryRepository;
use rednaoeasycalculationforms\core\Managers\EntrySaver\AIOEntry;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\pr\Managers\Editor\FormEntryEditor;

class EntryApi
{
    /**
     * @param $entryId
     * @param $mode 'raw'|'form'|'editor'
     * @return \rednaoeasycalculationforms\core\Managers\EntrySaver\AIOEntry|FormBuilder|null|AIOEntry|FormEntryEditor
     */
    public function Get($entryId,$mode='raw')
    {
        $entryRepository = new EntryRepository(AllInOneForms()->GetLoader());
        $entry= $entryRepository->LoadEntry($entryId);
        if($entry==null)
            return null;

        switch($mode)
        {
            case 'edit':
                $formEntryEditor=new FormEntryEditor(AllInOneForms()->GetLoader());
                 if(!$formEntryEditor->Initialize($entryId))
                    return null;
                return $formEntryEditor;
            case 'form':
                $formBuilder=AllInOneForms()->Form()->Get($entry->FormId);
                if($formBuilder==null)
                    return null;
                $formBuilder=new FormBuilder(AllInOneForms()->GetLoader(),$formBuilder,$entry);
                $formBuilder->Initialize();
                return $formBuilder;
            default:
                return $entry;
        }
    }



}

