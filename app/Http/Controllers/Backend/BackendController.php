<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Lang\CommonLang;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class BackendController extends Controller
{

    protected $except = ['login','logout'];
    protected $auth = [];
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $option = [];
        if (!is_null($this->auth)) {
            $option['only'] = $this->auth;
        }
        if (!is_null($this->except)) {
            $option['except'] = $this->except;
        }
        $this->middleware('auth:backend', $option);
    }

    /**
     * @param  LengthAwarePaginator|array  $page
     * @param  null|array  $list
     * @return array
     */
    protected function paginate($page, $list = null)
    {
        if ($page instanceof LengthAwarePaginator) {
            $total = $page->total();
            return [
                'total' => $page->total(),
                'page' => $total == 0 ? 0 : $page->currentPage(),
                'limit' => $page->perPage(),
                'pages' => $total == 0 ? 0 : $page->lastPage(),
                'rows' => $list ?? $page->items()
            ];
        }

        if ($page instanceof Collection) {
            $page = $page->toArray();
        }
        if (!is_array($page)) {
            return $page;
        }

        $total = count($page);
        return [
            'total' => $total,
            'page' => $total == 0 ? 0 : 1,
            'limit' => $total,
            'pages' => $total == 0 ? 0 : 1,
            'rows' => $page
        ];
    }
    /**
     * 分页信息
     * @param $page
     * @param  null  $list
     * @return JsonResponse
     */
    protected function successPaginate($page, $list = null)
    {
        return $this->success($this->paginate($page, $list));
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
    protected function outPut(array $response, $data = null, $info = ''): array
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
