<?php


namespace App\Libs\Repositories;


class GatewayApiRepository
{
    protected $rootUrl;

    /**
     * GatewayApiRepository constructor.
     */
    public function __construct()
    {
        $this->rootUrl = "https://api.ethiopia-covid19.com/";
    }

    public function getAllCommunityData($header)
    {
        $ch = curl_init($this->rootUrl . "gateway/communities");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function getCommunity($communityId, $header)
    {
        $ch = curl_init($this->rootUrl . "gateway/communities/" . $communityId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function createCommunityReport($communityData, $header)
    {
        $ch = curl_init($this->rootUrl . "gateway/community-report");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $communityData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function updateCommunityReport($communityDataUpdate, $header)
    {
        $ch = curl_init($this->rootUrl . "gateway/communities");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $communityDataUpdate);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
