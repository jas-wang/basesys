<?php

namespace App\Services\Goods;


use App\Models\Goods\Cate;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 商品
 */
class GoodsService extends BaseService
{


    /**
     * 获取分类列表
     * @param int $page
     * @param int $limit
     * @param array $form
     * @param $order
     * @param $columns
     * @return LengthAwarePaginator
     */
    public function getCateList(int $page, int $limit,$columns = ['*'])
    {
        $query = Cate::query();
        return $query->paginate($limit, $columns, 'page', $page);
    }

    /**
     * 获取分类列表（无线分类）
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getParentCateList()
    {
        return  Cate::with('children')->select()->get();
    }

    /**
     * 获取指定分类列表
     * @param int $id
     * @return array
     */
    public function getCate($id)
    {
        $cateIdArr = [];
        $data =   Cate::with('children')->where('id',$id)->select()->first();
        if(empty($data)){
            return  $cateIdArr;
        }
        $cateIdArr[] = $data->id;
        if(!empty($data->children)){
            $this->getChildrenCateId($data->children,$cateIdArr);
        }
       return $cateIdArr;
    }

    /**
     * 获取分类id
     * @param $child
     * @param $cateIdArr
     * @return mixed
     */
    private function getChildrenCateId($child,&$cateIdArr){
       $child = $child->toarray();
        foreach ($child as $item){
            $cateIdArr[] = $item['id'];
            if(isset($item['children']) && !empty($item['children'])){
                $this->getChildrenCateId($item['children'],$cateIdArr);
            }
        }
       return $cateIdArr;
    }

}


