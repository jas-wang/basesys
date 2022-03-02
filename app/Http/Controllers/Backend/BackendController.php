<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Lang\CommonLang;
use http\Client\Response;
use Illuminate\Http\JsonResponse;

class BackendController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:backend', ['except' => ['login']]);
    }

    /**
     * 成功
     * @param $data
     * @return mixed
     */
    protected function success($data = null)
    {
        return $this->outPut(CommonLang::SUCCESS, $data);
    }

    /**
     * 失败
     * @param array $response
     * @param string $info
     * @return mixed
     */
    protected function fail(array $response = CommonLang::FAIL, string $info = '')
    {
        return $this->outPut($response, null, $info);
    }

    /**
     * 封装返回信息
     * @param array $response
     * @param $data
     * @param $info 信息
     * @return array
     */
    protected function outPut(array $response, $data = null, $info = '')
    {
        list($errno, $errmsg) = $response;
        $ret = ['code' => $errno, 'message' => $info ?: $errmsg];
        if (!is_null($data)) {
            if (is_array($data)) {
                $data = array_filter($data, function ($item) {
                    return $item !== null;
                });
            }
            $ret['data'] = $data;
        }

        return $ret;
    }
}