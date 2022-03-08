<?php


namespace App\Http\Controllers\Backend;


class MainController extends BackendController
{
    public function count()
    {
       $data = ['newUser'=>10,'order'=>200,'money'=>222];
        return $this->success($data);

    }

}
