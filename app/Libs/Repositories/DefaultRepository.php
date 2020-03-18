<?php


namespace App\Libs\Repositories;


class DefaultRepository
{

    /**
     * DefaultRepository constructor.
     */
    public function __construct()
    {
    }

    public function queryBuilder($query, $queryData)
    {
        if ($queryData) {
            foreach ($queryData as $key => $value) {
                $type = strtoupper(substr($key, -1));
                if ((substr($key, -2, -1) === ':') && (in_array($type, ['D', 'V']))) {
                    if (substr($key, -4, -2) === '>=') {
                        $this->getQuery($query, substr($key, 0, -4), $value, '>=', substr($key, -1));
                    } elseif (substr($key, -4, -2) === '<=') {
                        $this->getQuery($query, substr($key, 0, -4), $value, '<=', substr($key, -1));
                    } elseif (substr($key, -4, -2) === '!=') {
                        $this->getQuery($query, substr($key, 0, -4), $value, '!=', substr($key, -1));
                    } elseif (substr($key, -3, -2) === '>') {
                        $this->getQuery($query, substr($key, 0, -3), $value, '>', substr($key, -1));
                    } elseif (substr($key, -3, -2) === '<') {
                        $this->getQuery($query, substr($key, 0, -3), $value, '<', substr($key, -1));
                    } elseif (substr($key, -3, -2) === '=') {
                        $this->getQuery($query, substr($key, 0, -3), $value, '=', substr($key, -1));
                    }
                } else {
                    $query->where($key, '=', $value);
                }
            }
        }
        return $query;
    }

    private function getQuery($query, $key, $value, $comp, $type)
    {
        if (strtoupper($type) === 'D') {
            $query->whereDate($key, $comp, $value);
        } elseif (strtoupper($type) === 'V') {
            $query->where($key, $comp, $value);
        }
        return $query;
    }
}
