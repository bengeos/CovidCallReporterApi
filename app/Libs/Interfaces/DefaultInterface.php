<?php


namespace App\Libs\Interfaces;


interface DefaultInterface
{
    public function getItem($id, $queryData=null); // Get Item
    public function getItemBy($queryData=null); //
    public function getAll($queryData=null); // Get All Data
    public function getAllPaginated($pagination_size=10, $queryData=null); // Get All Data In Pagination
    public function addNew($inputData);
    public function updateItem($id, $updateData, $queryData=null);
    public function updateItemBy($queryData, $updateData);
    public function deleteItem($id, $queryData=null);
    public function deleteItemBy($queryData=null);
}
