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
            'gender' => Str::title($this->data['gender'] ?? ''),

            'reportRegion' => [
                'id' => $this->data['report_region_id'] ?? null,
                'name' => Region::findOrNew($this->data['report_region_id'] ?? '')->name ?? '',
            ],

            'region' => $this->dynamicFind(Region::class, 'region_id'),
            'zone' => $this->dynamicFind(Zone::class, 'zone_id'),
            'woreda' => $this->dynamicFind(Wereda::class, 'wereda_id'),
            'city' => $this->dynamicFind(City::class, 'city_id'),
            'subcity' => $this->dynamicFind(SubCity::class, 'sub_city_id'),
            'kebele' => $this->dynamicFind(Kebele::class, 'kebele_id'),

            'createdBy' => $this->dynamicFind(User::class, 'created_by'),

            'phoneNumber' => $this->data['phone'] ?? null,
            'secondPhoneNumber' => $this->data['second_phone'] ?? null,

            'reportType' => $this->data['report_type'] ?? null,

            'description' => $this->data['description'] ?? null,

            'travelHx' => $this->data['is_travel_hx'] ?? false,
            'haveSex' => $this->data['is_contacted_with_pt'] ?? false,
            'visitedAnimal' => $this->data['is_visited_animal'] ?? false,
            'visitedHf' => $this->data['is_visited_hf'] ?? false,

            'rumorTypes' => $this->data['rumor_types'] ?? null,
        ]);
    }

    private function dynamicFind(string $class, string $id): ?array
    {
        if ($this->isValidEloquentClass($class) && isset($this->data[$id])) {
            /** @var Model $model */
            $model = $class::find($this->data[$id]);

            if ($model) return $model->jsonSerialize();
        }

        return null;
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

    private function isValidEloquentClass(string $class): bool
    {
        return class_exists($class) && is_subclass_of($class, Model::class);
    }
}
