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

            'age' => $this->data['age'] ?? '',
            'gender' => Str::title($this->data['gender']),

            'reportRegion' => [
                'id' => $this->data['report_region_id'],
                'name' => Region::find($this->data['report_region_id'])->name ?? '',
            ],

            'region' => $this->serialize(Region::find($this->data['region_id'])),
            'zone' => $this->serialize(isset($this->data['zone_id']) ? Zone::find($this->data['zone_id']) : null),
            'woreda' => $this->serialize(isset($this->data['wereda_id']) ? Wereda::find($this->data['wereda_id']) : null),
            'city' => $this->serialize(isset($this->data['city_id']) ? City::find($this->data['city_id']) : null),
            'subcity' => $this->serialize(isset($this->data['sub_city_id']) ? SubCity::find($this->data['sub_city_id']) : null),
            'kebele' => $this->serialize(isset($this->data['kebele_id']) ? Kebele::find($this->data['kebele_id']) : null),

            'createdBy' => $this->serialize(isset($this->data['created_by']) ? User::find($this->data['created_by']) : null),

            'phoneNumber' => isset($this->data['phone']) ? $this->data['phone'] : null,
            'secondPhoneNumber' => isset($this->data['second_phone']) ? $this->data['second_phone'] : null,

            'reportType' => isset($this->data['report_type']) ? $this->data['report_type'] : null,

            'description' => isset($this->data['description']) ? $this->data['description'] : null,

            'travelHx' => $this->data['is_travel_hx'] ?? false,
            'haveSex' => $this->data['is_contacted_with_pt'] ?? false,
            'visitedAnimal' => $this->data['is_visited_animal'] ?? false,
            'visitedHf' => $this->data['is_visited_hf'] ?? false,

            'rumorTypes' => $this->data['rumor_types'] ?? null,
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
