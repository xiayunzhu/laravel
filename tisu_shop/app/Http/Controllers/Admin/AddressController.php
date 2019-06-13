<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ml\Response\Result;

class AddressController extends BaseController
{

    /*
     * 字段
     * */
    private $fields = [ 'province', 'city', 'country'];

    /*
     * 列表
     * */
    public   function  index(Request $request, Address $product){
        return $this->backend_view('address.index');

    }

    public function list(Request $request, Result $result)
    {
        $query = Address::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
//            dd($queryFields);
//            exit;
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);
        return $result->toArray();
    }
    /*
     * 新增页面
    */
    public function create(Address $address)
    {
        return $this->backend_view('address.create_edit', compact('address'));
    }
    /*
     * 添加
     */
    public function store(Request $request, Result $result)
    {
        try {
//            dd($request->all());
//            exit;
            $model=Address::create($request->only($this->fields));
//
            $result->succeed($model);

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();
    }

    /*
    * 编辑页面
    */
    public function edit(Address $address)
    {
        return $this->backend_view('address.create_edit', compact('address'));
    }

    /*
     * 编辑数据
     */
    public function  update(Request $request, Address $address, Result $result){
        try {
//            dd($request->all());
//            exit;
            $address->update($request->only($this->fields));
            $result->succeed($address);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();

      /*  code:
        message: "操作成功",
        model
        success: true;
      */
    }

    /*
     *单个 删除数据
    */
    public function destroy(Address $address, Result $result)
    {
//       dd($address);
//        exit;
        if (!$address) {
            $result->failed('已删除或不存在');
        } else {

            //执行删除
            $del = $address->delete();
            if ($del) {
                $result->succeed($address);
            } else {
                $result->failed('删除失败');
            }
        }
        return $result->toArray();
   }
   /*
    * 多个删除数据
   */
   public function  destroyBat(Address $address,Result $result)
   {
      $ids=\request('ids');
//      dd($ids);
//      exit;
      if($ids &&is_array($ids)){
          $dels=$address::whereIn('id',$ids)->delete();
          if($dels>0){
              $result->success();
          }else{
              $result->failed('删除失败');
          }
      }else{
              $result->failed('参数错误');
          }
          return $result->toArray();

      }


   /*
    * list
    * */








}

