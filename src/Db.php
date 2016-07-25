<?php

namespace Daydiff\SimpleDb;

/**
 * Description of Db
 *
 * @author aleksandr.tabakov
 */
class Db
{
    /**
     * @var \PDO
     */
    private $connection;

    /**
     * Creates Db insatance
     *
     * @param string $dsn DSN in the format "mysql:host=localhost;dbname=test;port=3306;charset=utf8"
     * @param string $user
     * @param string $password
     * @param array $options
     */
    public function __construct($dsn, $user, $password, $options = [])
    {
        $default = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
        $options = array_merge($default, $options);
        $this->connection = new \PDO($dsn, $user, $password, $options);
    }

    /**
     * Returns a single scalar value from first row of result set
     *
     * @param string $query
     * @param array $params
     * @return string|null
     */
    public function scalar($query, $params = [])
    {
        return $this->exec($query, $params)->fetchColumn();
    }

    /**
     * Returns one row from result set
     *
     * @param string $query
     * @param array $params
     * @return array|null
     */
    public function one($query, $params = [])
    {
        return $this->exec($query, $params)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns all rows from result set
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function all($query, $params = [])
    {
        return $this->exec($query, $params)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns first column from result set
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function column($query, $params = [])
    {
        $sth = $this->exec($query, $params);

        while (false !== ($column = $sth->fetchColumn())) {
            $items[] = $column;
        }

        return $items;
    }

    /**
     * Executes the query and returns resulting PDOStatement object
     *
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function exec($query, $params = [])
    {
        $sth = $this->connection->prepare($query);
        $sth->execute($params);
        return $sth;
    }

    /**
     * Returns the ID of the last inserted row
     *
     * @return integer
     */
    public function insertedId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Quotes the param
     *
     * @param string|integer|float $param
     * @return string
     */
    public function quote($param)
    {
        return $this->connection->quote($param);
    }

}
