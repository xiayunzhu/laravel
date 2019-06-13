<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/20
 * Time: 22:08
 */

namespace App\Handlers;


use App\Models\Region;
use Illuminate\Http\Request;

class RegionsHandler
{
    private $cols = ['id', 'pid', 'name'];
    const REGION_ID = ['id','name'];


    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function list(Request $request)
    {
        $province = \request('province');
        $city = \request('city');
        $district = \request('district');

        ## 省份
        if (empty($province) && empty($city) && empty($district)) {
            return $this->provinces();
        }

        if (!empty($province) && empty($city)) {
            return $this->cities($province);
        }

        if (!empty($city) && empty($district)) {
            return $this->cities($city);
        }

        return $this->region($district);

    }

    /**
     * 所有的省份
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function provinces()
    {
        $query = Region::query();
        $query->where('pid', 0);
        $query->orderBy('id', 'asc');
        $data = $query->get($this->cols);
        return $data;
    }

    /**
     *
     * @param $province_id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function cities($province_id)
    {

        $query = Region::query();
        $query->where('pid', $province_id);
        $query->orderBy('id', 'asc');
        $data = $query->get($this->cols);
        return $data;
    }

    public function districts($city_id)
    {
        $query = Region::query();
        $query->where('pid', $city_id);
        $query->orderBy('id', 'asc');
        $data = $query->get($this->cols);
        return $data;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function region($id)
    {
        $query = Region::query();
        $query->where('id', $id);
        $data = $query->get($this->cols);
        return $data;
    }
    /**
     * 根据name查ID
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function region_id($name){
        $query = Region::query();
        $query->where('name', 'like', $name . '%');
        $data = $query->first(self::REGION_ID);
        return $data->toArray();
    }

}