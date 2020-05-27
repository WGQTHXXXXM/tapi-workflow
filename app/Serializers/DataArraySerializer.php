<?php


namespace App\Serializers;

use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class DataArraySerializer extends ArraySerializer
{


    protected $status_code;
    protected $message;

    public function __construct($status_code = 200, $message = '')
    {
        $this->message = $message;

        if ($status_code >= 200 && $status_code < 300) {
            $this->status_code = $status_code;
        } else {
            throw new \ErrorException('status_code value is wrong');
        }
    }

    public function item($resourceKey, array $data)
    {
        return [
            'code' => 0,
            'message' => $this->message,
            'businessObj' => $data,
        ];
    }

    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return [
            'code' => 0,
            'message' => $this->message,
            'businessObj' => $data,
        ];
    }


}
