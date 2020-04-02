<?php

namespace App\Events;

use Str;
use App\User;
use Illuminate\Database\Eloquent\Model;
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

        return $this->stripNulls([
            'firstName' => $names[0],
            'middleName' => $names[1] ?? '',
            'lastName' => $names[2] ?? '',

            'age' => $this->data['age'],
            'gender' => Str::title($this->data['gender']),

            'reportRegion' => [
                'id' => $this->data['report_region_id'],
                'name' => Region::find($this->data['report_region_id'])->name ?? '',
            ],

            'region' => $this->serialize(Region::find($this->data['region_id'])),
            'zone' => $this->serialize(Zone::find($this->data['zone_id'])),
            'woreda' => $this->serialize(Wereda::find($this->data['wereda_id'])),
            'city' => $this->serialize(City::find($this->data['city_id'])),
            'subcity' => $this->serialize(SubCity::find($this->data['sub_city_id'])),
            'kebele' => $this->serialize(Kebele::find($this->data['kebele_id'])),

            'createdBy' => $this->serialize(User::find($this->data['created_by'])),

            'phoneNumber' => $this->data['phone'],
            'secondPhoneNumber' => $this->data['second_phone'],

            'reportType' => $this->data['report_type'],

            'description' => $this->data['description'],

            'travelHx' => $this->data['is_travel_hx'] ?? false,
            'haveSex' => $this->data['is_contacted_with_pt'] ?? false,
            'visitedAnimal' => $this->data['is_visited_animal'] ?? false,
            'visitedHf' => $this->data['is_visited_hf'] ?? false,

            'rumorTypes' => $this->data['rumor_types'],
        ]);
    }

    private function serialize(?Model $model)
    {
        return ($model) ? $model->jsonSerialize() : null;
    }

    private function stripNulls(?array $array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->stripNulls($value);
            }
        }

        return array_filter($array, function ($value) {
            return !is_null($value);
        });
    }
}
