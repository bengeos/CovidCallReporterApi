<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CitiesRepository;
use App\Libs\Repositories\KebeleRepository;
use App\Libs\Repositories\RegionRepository;
use App\Libs\Repositories\SubCitiesRepository;
use App\Libs\Repositories\WeredaRepository;
use App\Libs\Repositories\ZoneRepository;
use Illuminate\Http\Request;


class CsvImportController extends Controller
{

    protected $regionRepo;
    /**
     * CsvImportController constructor.
     */

    public function __construct(RegionRepository $regionRepository,
                                ZoneRepository $zoneRepository,
                                WeredaRepository $weredaRepository,
                                CitiesRepository $citiesRepository,
                                SubCitiesRepository $subCitiesRepository,
                                KebeleRepository $kebeleRepository)
    {
        $this->regionRepo = $regionRepository;
        $this->zoneRepo = $zoneRepository;
        $this->weredaRepo = $weredaRepository;
        $this->cityRepo = $citiesRepository;
        $this->subCityRepo = $subCitiesRepository;
        $this->kebeleRepo = $kebeleRepository;
    }

    public function importStateStructureCsv(Request $request)
    {
        if ($request != null) {
            $file = $request->file('file');

            // File Details
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

            // Valid File Extensions
            $valid_extension = array("csv");

            // 2MB in Bytes
            $maxFileSize = 2097152;

            // Check file extension
            if (in_array(strtolower($extension), $valid_extension)) {
                // Check file size
                if ($fileSize <= $maxFileSize) {
                    // File upload location
                    $location = 'uploads';
                    // Upload file
                    $file->move($location, $filename);
                    // Import CSV to Database
                    $filepath = public_path($location . "/" . $filename);

                    // Reading file
                    $file = fopen($filepath, "r");

                    $importData_arr = array();
                    $i = 0;
                    while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                        $num = count($filedata);
                        for ($c=0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata [$c];
                        }
                        $i++;
                    }
                    fclose($file);
//                    Insert Regions
//                    for ($d=1; $d < count($importData_arr); $d++) {
//                        if (($importData_arr[$d][0] != $importData_arr[$d-1][0]) & $importData_arr[$d][0] != null)  {
//                            $insertData = array(
//                                "name"=>$importData_arr[$d][0],
//                                "latitude"=>$importData_arr[$d][1],
//                                "longitude"=>$importData_arr[$d][2],
//                                "description"=>$importData_arr[$d][3]
//                            );
//                            $this->regionRepo->addNew($insertData);
//                        }
//                    }


                    //Insert Zones
                    for ($d=1; $d < count($importData_arr); $d++) {
                        if (($importData_arr[$d][5] != $importData_arr[$d-1][5]) & $importData_arr[$d][5] != null)  {
                            $insertData = array(
                                "region_id"=>$importData_arr[$d][4],
                                "name"=>$importData_arr[$d][5],
                                "latitude"=>$importData_arr[$d][6],
                                "longitude"=>$importData_arr[$d][7],
                                "description"=>$importData_arr[$d][8]
                            );
                            $this->zoneRepo->addNew($insertData);
                        }
                    }

                    //Insert Wereda
                    for ($d=1; $d < count($importData_arr); $d++) {
                        if (($importData_arr[$d][10] != $importData_arr[$d-1][10]) & $importData_arr[$d][10] != null)  {
                            $insertData = array(
                                "zone_id"=>$importData_arr[$d][9],
                                "name"=>$importData_arr[$d][10],
                                "latitude"=>$importData_arr[$d][11],
                                "longitude"=>$importData_arr[$d][12],
                                "description"=>$importData_arr[$d][13]
                            );
                            $this->weredaRepo->addNew($insertData);
                        }
                    }

                    //Insert City
                    for ($d=1; $d < count($importData_arr); $d++) {
                        if (($importData_arr[$d][15] != $importData_arr[$d-1][15]) & $importData_arr[$d][15] != null)  {
                            $insertData = array(
                                "wereda_id"=>$importData_arr[$d][14],
                                "name"=>$importData_arr[$d][15],
                                "latitude"=>$importData_arr[$d][16],
                                "longitude"=>$importData_arr[$d][17],
                                "description"=>$importData_arr[$d][18]
                            );
                            $this->cityRepo->addNew($insertData);
                        }
                    }

//                    Insert Subcity
                    for ($d=1; $d < count($importData_arr); $d++) {
                        if (($importData_arr[$d][20] != $importData_arr[$d-1][20]) & $importData_arr[$d][20] != null)  {
                            $insertData = array(
                                "city_id"=>$importData_arr[$d][19],
                                "name"=>$importData_arr[$d][20],
                                "latitude"=>$importData_arr[$d][21],
                                "longitude"=>$importData_arr[$d][22],
                                "description"=>$importData_arr[$d][23]
                            );
                            $this->subCityRepo->addNew($insertData);
                        }
                    }

                    //Insert Kebele
                    for ($d=1; $d < count($importData_arr); $d++) {
                        if (($importData_arr[$d][25] != $importData_arr[$d-1][25]) & $importData_arr[$d][25] != null)  {
                            $insertData = array(
                                "sub_city_id"=>$importData_arr[$d][24],
                                "name"=>$importData_arr[$d][25],
                                "latitude"=>$importData_arr[$d][26],
                                "longitude"=>$importData_arr[$d][27],
                                "description"=>$importData_arr[$d][28]
                            );
                            $this->kebeleRepo->addNew($insertData);
                        }

                    }
                }
            }
        }
    }
}