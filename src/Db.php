<?php

namespace Daydiff\SimpleDb;

/**
 * Description of Db
 *
 * @author aleksandr.tabakov
 */
class Db
{
    private $connection;

    public function __construct($dsn, $user, $password, $options = [])
    {
        $this->connection = new \PDO($dsn, $user, $password, $options);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
        $sth = $this->connection->prepare($query);
        $sth->execute($params);

        return $sth->fetchColumn();
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
        $sth = $this->connection->prepare($query);
        $sth->execute($params);

        return $sth->fetch(\PDO::FETCH_ASSOC);
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
        $sth = $this->connection->prepare($query);
        $sth->execute($params);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
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
        $sth = $this->connection->prepare($query);
        $sth->execute($params);
        $items = [];

        while (false !== ($column = $sth->fetchColumn())) {
            $items[] = $column;
        }

        return $items;
    }

    /**
     * Do query and returns resulting PDOStatement object
     *
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function query($query, $params = [])
    {
        $sth = $this->connection->prepare($query);
        $sth->execute($params);
        return $sth;
    }

    /**
     * Execute a query and returns a count of affected rows
     *
     * @param string $query
     * @param array $params
     * @return integer|null
     */
    public function exec($query, $params = [])
    {
        $sth = $this->connection->prepare($query);
        $sth->execute($params);
        $count = $sth->rowCount();

        return $count;
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
