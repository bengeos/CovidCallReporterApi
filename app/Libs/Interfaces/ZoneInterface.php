<?php


namespace App\Libs\Interfaces;


interface ZoneInterface extends DefaultInterface
{
    public function getAllByRegion($region_id, $queryData=null);
    public function getAllByRegionPaginated($region_id, $pagination_size=10, $queryData=null);
}
