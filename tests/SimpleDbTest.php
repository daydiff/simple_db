<?php

namespace Daydiff\SimpleDb\Tests;

/**
 * Description of SimpleDbTest
 *
 * @author aleksandr.tabakov
 */
class SimpleDbTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Daydiff\SimpleDb\Db
     */
    private static $conn;

    public static function setUpBeforeClass()
    {
        self::$conn = new \Daydiff\SimpleDb\Db("sqlite::memory:");

        $lines = explode(';', file_get_contents(__DIR__ . '/migrations/sqlite.sql'));

        foreach ($lines as $line) {
            if (trim($line) !== '') {
                self::$conn->exec($line);
            }
        }
    }

    public function testInsert()
    {
        $query = "INSERT INTO test (msg) VALUES (:msg)";
        self::$conn->exec($query, ['msg' => 'msg 1']);
        $this->assertEquals(1, self::$conn->insertedId());
        self::$conn->exec($query, ['msg' => 'msg 2']);
        $this->assertEquals(2, self::$conn->insertedId());
    }

    public function testOne()
    {
        $item = self::$conn->one("SELECT * FROM test WHERE id = :id", ['id' => 2]);
        $this->assertArrayHasKey('id', $item);
        $this->assertEquals('2', $item['id']);
    }

    public function testScalar()
    {
        $item = self::$conn->scalar("SELECT msg FROM test WHERE id = :id", ['id' => 1]);
        $this->assertEquals('msg 1', $item);
    }

    public function testAll()
    {
        $items = self::$conn->all("SELECT * FROM test");
        $this->assertCount(2, $items);
    }

    public function testColumn()
    {
        $column = self::$conn->column("SELECT id FROM test");
        $this->assertCount(2, $column);
        $this->assertEquals('2', $column[1]);
    }
}
