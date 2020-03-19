<?php


namespace App\Libs\Interfaces;


interface CityInterface extends DefaultInterface
{
    public function getAllByWereda($wereda_id, $queryData=null);
    public function getAllByWeredaPaginated($wereda_id, $pagination_size=10, $queryData=null);
}
