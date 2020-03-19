<?php


namespace App\Libs\Interfaces;


interface WeredaInterface extends DefaultInterface
{
    public function getAllByZone($zone_id, $queryData=null);
    public function getAllByZonePaginated($zone_id, $pagination_size=10, $queryData=null);
}
