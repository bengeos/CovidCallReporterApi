<?php

namespace App\Http\Controllers\TestCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\GatewayApiRepository;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $gatewayRepo;

    /**
     * TestController constructor.
     * @param GatewayApiRepository $repository
     */
    public function __construct(GatewayApiRepository $repository)
    {
        $this->gatewayRepo = $repository;
    }

    public function makeGetRequest()
    {
        print_r(env('NEGARIT_API_URL', 'sadasd'));
//        $authorization = "Authorization: Bearer eyJraWQiOiJXN2NWbTJmQzlUN2g2QzI2ajZWb1JDV1pIUHRSUStJbzJvYTFyNFNIYUxVPSIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiI2NTRlZWc0dHJjYXA1ZTEwNXZ2dGszMjJvYiIsInRva2VuX3VzZSI6ImFjY2VzcyIsInNjb3BlIjoiaHR0cHM6XC9cL2FwaS5ldGhpb3BpYS1jb3ZpZDE5LmNvbVwvY292aWQtZ2F0ZXdheVwvcmVzb3VyY2UuZGVsZXRlIGh0dHBzOlwvXC9hcGkuZXRoaW9waWEtY292aWQxOS5jb21cL2NvdmlkLWdhdGV3YXlcL3Jlc291cmNlLnJlYWQgaHR0cHM6XC9cL2FwaS5ldGhpb3BpYS1jb3ZpZDE5LmNvbVwvY292aWQtZ2F0ZXdheVwvcmVzb3VyY2UuY3JlYXRlIGh0dHBzOlwvXC9hcGkuZXRoaW9waWEtY292aWQxOS5jb21cL2NvdmlkLWdhdGV3YXlcL3Jlc291cmNlLnVwZGF0ZSIsImF1dGhfdGltZSI6MTU4NTQyMTc1OSwiaXNzIjoiaHR0cHM6XC9cL2NvZ25pdG8taWRwLnVzLWVhc3QtMi5hbWF6b25hd3MuY29tXC91cy1lYXN0LTJfQUdDQTZYQlRQIiwiZXhwIjoxNTg1NDI1MzU5LCJpYXQiOjE1ODU0MjE3NTksInZlcnNpb24iOjIsImp0aSI6ImNmMjE3MDA0LTMwNzgtNGVhYi04YjUxLTY2Y2Y3YjUyN2UxMiIsImNsaWVudF9pZCI6IjY1NGVlZzR0cmNhcDVlMTA1dnZ0azMyMm9iIn0.QvjaIJhEMU8KRCn2zxjG39ikPclCzxplxck5uUPhqxYUp9-3Cs9CXQ46UCIYFhRCtcBqqRu_kDEounj2VmKJja04pTeHQ4Hmhjvj5aF65-bpHLrkBkAM05g5q0XoURfagLDbo5C9ZuCk-g-Jm5cbdrYAYJ18tQNzN8mfVQEs-thcw9uKCZknCzX-_PMIdQp_Ga_SMvjkT4ZTGPoXLQ4TtEql10YnfGNb6ZyefIZ2qeehrwGMzyzpwh9nyTGfAq3NYBD8giX0DFjw-H5gWGW21HV7aGDHEBO6K120Trz4VFuFJsjExjZmM3BwA-rApCMxraPmbJuSiQmbXTuCI8bXRw";
//
//        $data = $this->gatewayRepo->getAllCommunityData("Content-Type:application/json," . $authorization);
//        return response()->json(['status' => true, 'data' => $data]);
    }
}
