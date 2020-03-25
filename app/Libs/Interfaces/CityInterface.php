<?php


namespace App\Libs\Interfaces;


interface CityInterface extends DefaultInterface
{
    public function getAllByRegion($region_id, $queryData=null);
}
