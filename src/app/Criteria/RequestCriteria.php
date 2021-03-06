<?php
namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria as BaseRequestCriteria;

/**
 * Class RequestCriteria
 * @package App\Criteria
 */
class RequestCriteria extends BaseRequestCriteria
{

    /**
     * 应用过滤器,默认各字段之间使用 and 连接。若需要使用 or| 标记。例如:http://prettus.local/users?search=name:John;or|email:john@gmail.com&searchFields=name:like;email:=
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     * @author wanghaiming@vchangyi.com
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $fieldsSearchable = $repository->getFieldsSearchable();
        $search           = $this->request->get(config('repository.criteria.params.search', 'search'), null);
        $searchFields     = $this->request->get(config('repository.criteria.params.searchFields', 'searchFields'), null);
        $filter           = $this->request->get(config('repository.criteria.params.filter', 'filter'), null);
        $orderBy          = $this->request->get(config('repository.criteria.params.orderBy', 'orderBy'), null);
        $sortedBy         = $this->request->get(config('repository.criteria.params.sortedBy', 'sortedBy'), 'asc');
        $with             = $this->request->get(config('repository.criteria.params.with', 'with'), null);
        $sortedBy         = !empty($sortedBy) ? $sortedBy : 'asc';
        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {
            $searchFields = is_array($searchFields) || is_null($searchFields) ? $searchFields : explode(';', $searchFields);
            $fields       = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField = true;
            $searchData   = $this->parserSearchData($search);
            $search       = $this->parserSearchValue($search);
            $model        = $model->where(function ($query) use ($fields, $search, $searchData, $isFirstField) {
                /** @var Builder $query */
                foreach ($fields as $field => $condition) {
                    if (is_numeric($field)) {
                        $field     = $condition;
                        $condition = "=";
                    }
                    $value         = null;
                    $condition     = trim(strtolower($condition));
                    $isOrOperator = isset($searchData[$field]['operator']) && $searchData[$field]['operator'] === strtolower("or");
                    if (isset($searchData[$field]['value'])) {
                        $value = ($condition == "like" || $condition == "ilike") ? "%{$searchData[$field]['value']}%" : $searchData[$field]['value'];
                    } else {
                        if (!is_null($search)) {
                            $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
                        }
                    }
                    $relation = null;
                    if (stripos($field, '.')) {
                        $explode  = explode('.', $field);
                        $field    = array_pop($explode);
                        $relation = implode('.', $explode);
                    }
                    $modelTableName = $query->getModel()->getTable();
                    if ($isFirstField || !$isOrOperator) {
                        if (!is_null($value)) {
                            if (!is_null($relation)) {
                                $query->whereHas($relation, function ($query) use ($field, $condition, $value) {
                                    $query->where($field, $condition, $value);
                                });
                            } else {
                                $query->where($modelTableName . '.' . $field, $condition, $value);
                            }
                            $isFirstField = false;
                        }
                    } else {
                        if (!is_null($value)) {
                            if (!is_null($relation)) {
                                $query->orWhereHas($relation, function ($query) use ($field, $condition, $value) {
                                    $query->where($field, $condition, $value);
                                });
                            } else {
                                $query->orWhere($modelTableName . '.' . $field, $condition, $value);
                            }
                        }
                    }
                }
            });
        }
        if (isset($orderBy) && !empty($orderBy)) {
            $split = explode('|', $orderBy);
            if (count($split) > 1) {
                /*
                 * ex.
                 * products|description -> join products on current_table.product_id = products.id order by description
                 *
                 * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
                 * by products.description (in case both tables have same column name)
                 */
                $table      = $model->getModel()->getTable();
                $sortTable  = $split[0];
                $sortColumn = $split[1];
                $split      = explode(':', $sortTable);
                if (count($split) > 1) {
                    $sortTable = $split[0];
                    $keyName   = $table . '.' . $split[1];
                } else {
                    /*
                     * If you do not define which column to use as a joining column on current table, it will
                     * use a singular of a join table appended with _id
                     *
                     * ex.
                     * products -> product_id
                     */
                    $prefix  = rtrim($sortTable, 's');
                    $keyName = $table . '.' . $prefix . '_id';
                }
                $model = $model
                    ->leftJoin($sortTable, $keyName, '=', $sortTable . '.id')
                    ->orderBy($sortColumn, $sortedBy)
                    ->addSelect($table . '.*');
            } else {
                $model = $model->orderBy($orderBy, $sortedBy);
            }
        }
        if (isset($filter) && !empty($filter)) {
            if (is_string($filter)) {
                $filter = explode(';', $filter);
            }
            $model = $model->select($filter);
        }
        if ($with) {
            $with  = explode(';', $with);
            $model = $model->with($with);
        }
        return $model;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];
        if (stripos($search, ':')) {
            $fields = explode(';', $search);
            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $operator = "and";
                    if (stripos($field, '|') > -1) {
                        list($operator, $field) = explode('|', $field);
                    }
                    $searchData[$field]['value']    = $value;
                    $searchData[$field]['operator'] = $operator;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }
        return $searchData;
    }

}
