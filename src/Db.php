<?php

namespace Daydiff\SimpleDb;

/**
 * Description of Db
 *
 * @author aleksandr.tabakov
 */
class Db
{
    const DEFAULT_CHARSET = 'utf8';

    private $dsn;
    private $user;
    private $password;
    private $options;
    private $connection;
    private $error;

    public function __construct($dsn, $user, $password, $options = [])
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    /**
     * Returns the connection to the database
     *
     * @return \PDO
     */
    public function connection()
    {
        if ($this->connection == null) {
            $this->connection = new \PDO($this->dsn, $this->user, $this->password, $this->options);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return $this->connection;
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
        $sth = $this->connection()->prepare($query);
        $sth->execute($params);

        try {
            $scalar = $sth->fetchColumn();
            $this->error = null;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }

        return $scalar;
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
        $sth = $this->connection()->prepare($query);
        $sth->execute($params);

        try {
            $item = $sth->fetch(\PDO::FETCH_ASSOC);
            $this->error = null;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }

        return $item;
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
        $sth = $this->connection()->prepare($query);
        $sth->execute($params);

        try {
            $items = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $this->error = null;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return [];
        }

        return $items;
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
        $sth = $this->connection()->prepare($query);
        $sth->execute($params);
        $items = [];

        try {
            while (false !== ($column = $sth->fetchColumn())) {
                $items[] = $column;
            }
            $this->error = null;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return [];
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
        $sth = $this->connection()->prepare($query);
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
        $sth = $this->connection()->prepare($query);

        try {
            $sth->execute($params);
            $this->error = null;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }

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
        return $this->connection()->lastInsertId();
    }

    /**
     * Quotes the param
     *
     * @param string|integer|float $param
     * @return string
     */
    public function quote($param)
    {
        return $this->connection()->quote($param);
    }

    /**
     * Returns an error if it occured in the last query
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }
}
