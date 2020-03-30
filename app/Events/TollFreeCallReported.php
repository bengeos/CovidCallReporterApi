<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\{City, Kebele, Region, SubCity, Wereda, Zone};

class TollFreeCallReported
{
    use Dispatchable;

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getMappedData()
    {
        // TODO: Find a better strategy
        $names = explode(' ', $this->data['full_name'], 3);

        return [
            'firstName' => $names[0],
            'middleName' => $names[1] ?? '',
            'lastName' => $names[2] ?? '',

            'gender' => strtolower($this->data['gender']),

            'phoneNumber' => $this->data['phone'],
            'secondPhoneNumber' => $this->data['second_phone'],

            'age' => $this->data['age'],

            // FIXME: Need to be cached
            'region' => Region::find($this->data['report_region_id'])->name,
            'subcity' => SubCity::find($this->data['report_region_id'])->name,
            'zone' => Zone::find($this->data['report_region_id'])->name,
            'woreda' => Wereda::find($this->data['report_region_id'])->name,
            'kebele' => Kebele::find($this->data['report_region_id'])->name,
            'city' => City::find($this->data['report_region_id'])->name,

            'reportType' => $this->data['report_type'],

            'travelHx' => array_key_exists($this->data, 'is_travel_hx'),
            'haveSex' => array_key_exists($this->data, 'is_contacted_with_pt'),
            'description' => array_key_exists($this->data, 'is_visited_animal'),

            // FIXME: figure this out ...
            'rumorTypes' => [
                [
                    'id' => 2,
                    'name' => 'Cough',
                    'description' => null,
                    'createdAt' => '2020-03-26 07=>57=>59',
                    'updatedAt' => '2020-03-26 07=>57=>59',
                    'deletedAt' => null
                ],
                [
                    'id' => 3,
                    'name' => 'Headache',
                    'description' => null,
                    'createdAt' => '2020-03-26 07=>57=>59',
                    'updatedAt' => '2020-03-26 07=>57=>59',
                    'deletedAt' => null
                ]
            ],
        ];
    }
}
