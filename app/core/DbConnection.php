<?php

namespace app\core;

use Exception;

class DbConnection
{

    private $dbConnection;
    private $connectSettings;

    /**
     * @throws Exception
     */
    public function __construct($connectSettings = null)
    {
        if (isset($connectSettings)) {
            $this->connectSettings = $connectSettings;
        } else {
            $dbParams = file_exists(__DIR__ . '/../config/db.php') ? require __DIR__ . '/../config/db.php' : [];
            if (isset($dbParams) && !empty($dbParams)) {
                $this->connectSettings = $dbParams;
            } else {
                throw new Exception('Failed to connect to database');
            }
        }
        $this->connect();
    }

    /**
     * Database connection
     *
     * @return bool
     * @throws Exception
     */
    public function connect()
    {
        if ($this->dbConnection instanceof \mysqli && $this->dbConnection->ping()) {
            return true;
        }

        $connection = new \mysqli(
            $this->connectSettings['host'],
            $this->connectSettings['user'],
            $this->connectSettings['password'],
            $this->connectSettings['db_name']
        );

        if ($connection->connect_error) {
            throw new Exception($connection->connect_error);
        }

        $this->dbConnection = $connection;

        $connection->query("SET NAMES 'utf8'");
        $connection->query("SET CHARACTER SET 'utf8'");
        $connection->query("SET SESSION collation_connection = 'utf8_general_ci'");
        return true;
    }

    /**
     * Close database connection
     *
     * @return void
     */
    private function disconnect()
    {
        if ($this->dbConnection instanceof \mysqli && $this->dbConnection->ping()) {
            $this->dbConnection->close();
        }
    }

    /**
     * Creating records in the database
     *
     * @param string $table - table name
     * @param array $values - array with parameters to write
     * @param bool $close - flag indicating the connection is closed
     * @return $this
     * @throws Exception
     */
    public function insert(string $table, array $values, bool $close = false): DbConnection
    {
        $this->connect();
        
        $cols = array_keys($values);
        $sql = "INSERT INTO " . $table . " (" . implode(',', $cols) . ") VALUES ('" . implode("', '", $values) . "')";

        $query = $this->dbConnection->query($sql);

        if (!$query) {
            throw new Exception($this->dbConnection->error);
        }

        if ($close) {
            $this->disconnect();
        }

        return $this;
    }

    /**
     * Removing records from the database
     *
     * @param string $table - table name
     * @param array $where - search options
     * @param bool $close - flag indicating the connection is closed
     * @return $this
     * @throws Exception
     */
    public function delete(string $table, array $where, bool $close = false): DbConnection
    {
        $this->connect();

        $whereSql = $this->arrayToSqlPrams($where, ' AND ');

        $sql = "DELETE FROM " . $table . " WHERE " . $whereSql;
        $query = $this->dbConnection->query($sql);
        if (!$query) {
            throw new Exception($this->dbConnection->error);
        }

        if ($close) {
            $this->disconnect();
        }

        return $this;
    }

    /**
     * Updating records in the database
     *
     * @param string $table - table name
     * @param array $values - array with parameters to write
     * @param bool $close - flag indicating the connection is closed
     * @return $this
     * @throws Exception
     */
    public function update(string $table, array $values, array $where, bool $close = false): DbConnection
    {
        $this->connect();

        $whereSql = $this->arrayToSqlPrams($where, ' AND ');
        $valuesSql = $this->arrayToSqlPrams($values);

        $sql = "UPDATE " . $table . " SET " . $valuesSql . " WHERE " . $whereSql;

        $query = $this->dbConnection->query($sql);
        if (!$query) {
            throw new Exception($this->dbConnection->error);
        }

        if ($close) {
            $this->disconnect();
        }

        return $this;
    }

    /**
     * Select records from the database
     *
     * @param string $table - table name
     * @param array $where - search options
     * @param string $select - received parameters
     * @param integer $limit - maximum number of received rows
     * @param integer $page - page
     * @param string $sort - attribute to sort
     * @param bool $close - flag indicating the connection is closed
     * @throws Exception
     */
    public function select(string $table, $select, array $where, int $limit = null, int $page = null, string $sort = null, bool $close = false)
    {
        $this->connect();

        if (!isset($select)) {
            $select = '*';
        }

        $whereSql = $this->arrayToSqlPrams($where, ' AND ');

        $sql = "SELECT " . $select . " FROM " . $table;

        if (!empty($whereSql)) {
            $sql .= " WHERE " . $whereSql;
        }

        if (!empty($sort)) {
            $sort = $this->dbConnection->real_escape_string($sort);
            $sql .= " ORDER BY " . $sort;
        }

        if ($limit) {
            $limit = $this->dbConnection->real_escape_string($limit);
            $sql .= " LIMIT ";
            if ($page) {
                $page = $this->dbConnection->real_escape_string($page);
                $sql .= ( $page - 1 ) * $limit . ', ';
            }
            $sql .= $limit;
        }

        $query = $this->dbConnection->query($sql);
        if (!$query) {
            throw new Exception($this->dbConnection->error);
        }

        if ($close) {
            $this->disconnect();
        }

        return $query->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Converting an array of parameters to sql
     *
     * @param array $array - parameter array
     * @param string $separator - separator
     * @return string
     */
    private function arrayToSqlPrams(array $array, string $separator = ', '): string
    {
        $sqlText = [];
        foreach ($array as $col => $value) {
            $col = $this->dbConnection->real_escape_string($col);
            $value = $this->dbConnection->real_escape_string($value);
            if (is_string($value)) {
                $sqlText[] = $col . ' = "' . $value . '"';
            } else if (is_numeric($value)) {
                $sqlText[] = $col . ' = ' . $value;
            }
        }

        return implode($separator, $sqlText);
    }

    /**
     * Getting the total number of records in a table
     *
     * @param string $table - table name
     * @param bool $close - flag indicating the connection is closed
     * @return mixed
     * @throws Exception
     */
    public function count(string $table, bool $close)
    {
        $this->connect();

        $sql = "SELECT COUNT(*) FROM " . $table . ";";
        $query = $this->dbConnection->query($sql);
        if (!$query) {
            throw new Exception($this->dbConnection->error);
        }

        if ($close) {
            $this->disconnect();
        }
        return $query->fetch_all(MYSQLI_ASSOC)[0]['COUNT(*)'];
    }
}