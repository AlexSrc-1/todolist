<?php

namespace app\core;

abstract class Model {

    private static $select;
    private static $limit;
    private static $page;
    private static $sort;
    public $id;
    public $params;
	
	public function __construct($params = [])
    {
        $this->params = array_keys($params);
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
	}

    /**
     * Get all models
     *
     * @param $where - search options
     * @return array
     */
    public static function findAll($where): array
    {
        $params = App::$db->select(
            static::tableName(),
            static::$select,
            $where,
            static::$limit,
            static::$page,
            static::$sort
        ) ?? [];
        return static::getModels($params);
    }

    /**
     * Get one model
     *
     * @param $where - search options
     * @return Model|mixed|null
     */
    public static function findOne($where)
    {
        $params = App::$db->select(static::tableName(), static::$select, $where, 1)[0] ?? null;
        return static::getModel($params);
    }

    /**
     * Translation of the received data array from the request in the model
     *
     * @param $arrayParams - data array
     * @return array
     */
    private static function getModels($arrayParams): array
    {
        $models = [];
        foreach ($arrayParams as $params) {
            $models[] = static::getModel($params);
        }
        return $models;
    }

    /**
     * Translation of the received data array from the request into the model
     *
     * @param $params - data array
     * @return Model|mixed|null
     */
    private static function getModel($params)
    {
        if (!isset($params)) {
            return null;
        }

        $className = static::class;
        if (class_exists($className)) {
            $model = new $className($params);
            return $model;
        }
        return null;
    }

    /**
     * Change received parameters
     *
     * @param $select
     * @return static
     */
    public static function select($select = '*')
    {
        static::$select = $select;
        return new static;
    }

    /**
     * Change the number of objects received
     *
     * @param $limit
     * @return static
     */
    public static function limit($limit = 3)
    {
        static::$limit = $limit;
        return new static;
    }

    /**
     * Edit page
     *
     * @param $page
     * @return static
     */
    public static function page($page = 1)
    {
        static::$page = $page;
        return new static;
    }

    /**
     * Change attribute for sorting
     *
     * @param $sort
     * @return static
     */
    public static function sort($sort)
    {
        static::$sort = $sort;
        return new static;
    }

    /**
     * Table name in the database
     *
     * @return null
     */
    public static function tableName()
    {
        return null;
    }

    /**
     * Save model
     */
    public function save()
    {
        if ($this->beforeSave()) {
            if ($this->id) {
                $where = ['id' => $this->id];
                if (self::update($this->getValues(), $where)) {
                    $this->afterSave();
                    return true;
                }
            } else {
                $this->id = intval(self::insert($this->getValues())['id']);
                $this->afterSave();
                return true;
            }
        }
        return false;
    }

    /**
     * Update model
     */
    private static function update($values, $where)
    {
        return App::$db->update(static::tableName(), $values, $where);
    }

    /**
     * Insert model
     */
    private static function insert($values)
    {
        if (App::$db->insert(static::tableName(), $values)) {
            return App::$db->select(static::tableName(), 'id', $values, 1)[0] ?? null;
        }
    }

    /**
     * Get values model
     *
     * @return array
     */
    private function getValues()
    {
        $values = [];
        foreach ($this->params as $param) {
            if (is_string($this->$param)) {
                $values[$param] = htmlspecialchars(addcslashes($this->$param, '.\'"\+*?[^]($)'));
            } else {
                $values[$param] = $this->$param;
            }
        }

        return $values;
    }

    /**
     * Event called before saving
     *
     * @return bool
     */
    public function beforeSave(): bool
    {
        return true;
    }

    /**
     * Event called after save
     *
     * @return bool
     */
    public function afterSave(): bool
    {
        return true;
    }

    /**
     * Building links for pagination
     *
     * @param $list_class
     * @return string|null
     */
    public static function createLinks($list_class) {
        $limit = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 3;
        $page = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
        $links = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 7;
        $sort = ( isset( $_GET['sort'] ) ) ? '&sort=' . $_GET['sort'] : null;
        $count = App::$db->count(static::tableName(), 'id') ?? 0;
        if ($limit >= $count) {
            return null;
        }
        $last = ceil($count / $limit);
        $start = (($page - $links ) > 0 ) ? $page - $links : 1;
        $end = (($page + $links ) < $last) ? $page + $links : $last;
        $html = '<ul class="' . $list_class . '">';
        $class = ( $page == 1 ) ? "disabled" : "";
        $html .= '<li class="page-item ' . $class . '"><a class="page-link" href="?limit=' . $limit . '&page=' . ( $page - 1 ) . $sort . '">&laquo;</a></li>';
        if ( $start > 1 ) {
            $html .= '<li class="page-item"><a class="page-link" href="?limit=' . $limit . '&page=1' . $sort . '">1</a></li>';
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }

        for ( $i = $start ; $i <= $end; $i++ ) {
            $class = ( $page == $i ) ? "active" : "";
            $html .= '<li class="page-item ' . $class . '"><a class="page-link" href="?limit=' . $limit . '&page=' . $i . $sort . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            $html .= '<li class="page-item"><a class="page-link" href="?limit=' . $limit . '&page=' . $last . $sort . '">' . $last . '</a></li>';
        }

        $class = ($page == $last) ? "disabled" : "";
        $html .= '<li class="page-item ' . $class . '"><a class="page-link" href="?limit=' . $limit . '&page=' . ( $page + 1 ) . $sort . '">&raquo;</a></li>';

        $html .= '</ul>';

        return $html;
    }
}