<?php
/**
 * Created by IntelliJ IDEA.
 * User: xh
 * Date: 16/5/13
 * Time: 16:03
 */

namespace App\Http\Controllers\Traits;

use App\Models\BaseModel;
use App\Serializers\DataArraySerializer;
use App\Transformers\DataTransformer;
use App\Transformers\DefaultTransformer;
use Illuminate\Http\Response;

trait ResponseApiTrait
{
    /**
     * 返回响应数据
     * @param string $type 调用类型
     * @param BaseModel|array $collection 集合值
     * @param string $transformer 筛选器
     * @param int $status_code 相应状态
     * @param string $message 返回消息
     * @return mixed
     */
    private function responseAPI($type, $collection, $transformer = '', $status_code = 200, $message = 'successful')
    {

        if (!$transformer) {
            $transformer = new DefaultTransformer();
        }

        if (is_array($collection)) {
            $collection = (object)$collection;
            $transformer = new DataTransformer();
        }

        return $this->response->$type($collection, $transformer, [], function ($resource, $fractal) use ($status_code, $message) {
            $fractal->setSerializer(new DataArraySerializer($status_code, $message));
        })->setStatusCode($status_code);
    }

    /**
     * 返回单列数据
     * @param $item
     * @param string $transformer
     * @return mixed
     */
    public function responseItem($item, $transformer = '')
    {
        return $this->responseAPI('item', $item, $transformer);
    }

    /**
     * 返回集合数据
     * @param $collection
     * @param string $transformer
     * @return mixed
     */
    public function responseCollection($collection, $transformer = '')
    {
        if (is_array($collection)) {
            $collection = collect($collection);
        }
        return $this->responseAPI('collection', $collection, $transformer);
    }

    /**
     * 返回分页数据
     * @param $collection
     * @param $transformer
     * @return mixed
     */
    public function responsePaginator($collection, $transformer = null)
    {
        return $this->responseAPI('paginator', $collection, $transformer);
    }

    /**
     * 返回更新成功数据
     * @param BaseModel|array $data
     * @param string $message
     * @param $transformer
     * @return mixed
     */
    public function responseUpdate($data = array(), $transformer = null, $message = '更新成功')
    {
        $status_code = Response::HTTP_CREATED;
        return $this->responseAPI('item', $data, $transformer, $status_code, $message);
    }

    /**
     * 返回创建成功数据
     * @param BaseModel|array $data
     * @param $transformer
     * @param string $message
     * @return mixed
     */
    public function responseCreated($data = array(), $transformer = null, $message = '创建成功')
    {
        $status_code = Response::HTTP_CREATED;
        return $this->responseAPI('item', $data, $transformer, $status_code, $message);
    }

    /**
     * 返回删除成功数据
     * @param BaseModel|array $data
     * @param $transformer
     * @param string $message
     * @return mixed
     */
    public function responseNoContent($data = array(), $transformer = null, $message = '删除成功')
    {
        $status_code = Response::HTTP_NO_CONTENT;
        return $this->responseAPI('item', $data, $transformer, $status_code, $message);
    }


}