<?php


namespace App\Libs\Interfaces;


interface CallReportInterface extends DefaultInterface
{
    public function getAllByUserPaginated($user_id, $pagination_size=10, $queryData=null); // Get All Data In Pagination
}
