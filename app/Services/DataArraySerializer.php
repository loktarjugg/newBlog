<?php
namespace App\Services;

use League\Fractal\Pagination\PaginatorInterface;
use Spatie\Fractalistic\ArraySerializer;

/**
 * Class DataArraySerializer
 * @package App\Services
 */
class DataArraySerializer extends ArraySerializer
{

    /**
     * @param $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey === false) {
            return $data;
        }
        return array($resourceKey ?: 'data' => $data);
    }

    /**
     * @param $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        if ($resourceKey === false) {
            return $data;
        }

        return array($resourceKey ?: 'data' => $data);
    }


    /**
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function paginator(PaginatorInterface $paginator)
    {
        $currentPage = (int) $paginator->getCurrentPage();
        $lastPage = (int) $paginator->getLastPage();

        $pagination = [
            'total' => (int) $paginator->getTotal(),
            'count' => (int) $paginator->getCount(),
            'per_page' => (int) $paginator->getPerPage(),
            'current_page' => $currentPage,
            'total_pages' => $lastPage,
        ];

        $pagination['links'] = [
            'previous' => $currentPage > 1 ? $paginator->getUrl($currentPage - 1) : $this->null(),
            'next' => $currentPage < $lastPage ? $paginator->getUrl($currentPage + 1) :$this->null()
        ];

        return ['pagination' => $pagination];
    }

    /**
     * @return null
     */
    public function null()
    {
        return null;
    }
}
