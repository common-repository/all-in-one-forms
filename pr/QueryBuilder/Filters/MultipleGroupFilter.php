<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Filters;


class MultipleGroupFilter extends FilterGroup
{
    /** @var FilterGroup[] */
    public $Groups;

    public function __construct($joinType = 'or')
    {
        parent::__construct(null, $joinType);
        $this->Groups = [];
    }

    /**
     * @param $group FilterGroup
     */
    public function AddGroup($group)
    {
        $this->Groups[]=$group;
    }

    public function CreateGroupString()
    {
         $filter='';
         foreach($this->Groups as $currentGroup)
         {
             if($filter!='')
                 $filter.=' '.$currentGroup->JoinType.' ';
             $filter.=$currentGroup->CreateGroupString();
         }

         if($filter!='')
             return ' ('.$filter.')';
         return '';
    }


}