<?php

use Classes\Query;
use Exceptions\BadSQLException;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase {

    // Test that we can actually create a query with a valid table name.
    public function testCreateQuery(): void {
        $query = Query::create('test');
        $this->assertEquals('\'test\'', $query->getFrom());

        $query = Query::create('test', 't');
        $this->assertEquals('\'test\' \'t\'', $query->getFrom());
    }

    // Test that we won't allow non-allowed characters for table names.
    public function testCreateQueryBadTableName(): void {
        $this->expectException(BadSQLException::class);
        Query::create('test; select * from user where true;');
    }

    // Test that we won't allow non-allowed characters for table aliases.
    public function testCreateQueryBadTableAlias(): void {
        $this->expectException(BadSQLException::class);
        Query::create('test', '; select * from user where true;');
    }

    // Test that normal usage of the select function works as expected
    public function testQuerySelect(): void {
        $query = Query::create('test')
            ->select('test.whatever');
        $this->assertEquals('\'test\'.\'whatever\'', $query->getSelect());

        $query->select('test.other');
        $this->assertEquals('\'test\'.\'whatever\', \'test\'.\'other\'', $query->getSelect());

        $query = Query::create('test', 't')
            ->select('t.whatever');
        $this->assertEquals('\'t\'.\'whatever\'', $query->getSelect());

        $query->select('t.other');
        $this->assertEquals('\'t\'.\'whatever\', \'t\'.\'other\'', $query->getSelect());
    }

    // Test that SQL injection doesn't work.
    public function testQueryBadSelect(): void {
        $this->expectException(BadSQLException::class);
        $query = Query::create('test')
            ->select('test.id; select * from user where true;');
    }

    public function testWhere(): void {
        // Test that where functions without params.
        $query = Query::create('test')
            ->where('test.column = 9');
        $this->assertEquals('\'test\'.\'column\' = 9', $query->getWhere());

        // Test that where works with params.
        $query = Query::create('test')
            ->where('test.column = :val', [':val' => 9]);
        $this->assertEquals('\'test\'.\'column\' = 9', $query->getWhere());

        // Test that where works with params where the param is part of a longer string. This is expected behavior,
        // but not something anyone will want to do.
        $query = Query::create('test')
            ->where('test.column = :value', [':val' => 9]);
        $this->assertEquals('\'test\'.\'column\' = 9ue', $query->getWhere());

        // Test that where does nothing with params that don't start with the right character.
        $query = Query::create('test')
            ->where('test.column = val', ['val' => 9]);
        $this->assertEquals('\'test\'.\'column\' = val', $query->getWhere());
    }

    public function testAndWhere(): void {
        // Test that where functions without params.
        $query = Query::create('test')
            ->where('test.column = 9')
            ->andWhere('test.other = true');
        $this->assertEquals('\'test\'.\'column\' = 9 AND \'test\'.\'other\' = true', $query->getWhere());

        // Test that where works with params.
        $query = Query::create('test')
            ->where('test.column = :val', [':val' => 9])
            ->andWhere('test.other = :bool', [':bool' => TRUE]);
        $this->assertEquals('\'test\'.\'column\' = 9 AND \'test\'.\'other\' = 1', $query->getWhere());

        // Test that where works with params where the param is part of a longer string. This is expected behavior,
        // but not something anyone will want to do.
        $query = Query::create('test')
            ->where('test.column = :value', [':val' => 9])
            ->andWhere('test.other = :boolean', [':bool' => TRUE]);
        $this->assertEquals('\'test\'.\'column\' = 9ue AND \'test\'.\'other\' = 1ean', $query->getWhere());

        // Test that where does nothing with params that don't start with the right character.
        $query = Query::create('test')
            ->where('test.column = val', ['val' => 9])
            ->andWhere('test.other = bool', ['bool' => TRUE]);
        $this->assertEquals('\'test\'.\'column\' = val AND \'test\'.\'other\' = bool', $query->getWhere());
    }

    public function testOrWhere(): void {
// Test that where functions without params.
        $query = Query::create('test')
            ->where('test.column = 9')
            ->orWhere('test.other = true');
        $this->assertEquals('\'test\'.\'column\' = 9 OR \'test\'.\'other\' = true', $query->getWhere());

        // Test that where works with params.
        $query = Query::create('test')
            ->where('test.column = :val', [':val' => 9])
            ->orWhere('test.other = :bool', [':bool' => TRUE]);
        $this->assertEquals('\'test\'.\'column\' = 9 OR \'test\'.\'other\' = 1', $query->getWhere());

        // Test that where works with params where the param is part of a longer string. This is expected behavior,
        // but not something anyone will want to do.
        $query = Query::create('test')
            ->where('test.column = :value', [':val' => 9])
            ->orWhere('test.other = :boolean', [':bool' => TRUE]);
        $this->assertEquals('\'test\'.\'column\' = 9ue OR \'test\'.\'other\' = 1ean', $query->getWhere());

        // Test that where does nothing with params that don't start with the right character.
        $query = Query::create('test')
            ->where('test.column = val', ['val' => 9])
            ->orWhere('test.other = bool', ['bool' => TRUE]);
        $this->assertEquals('\'test\'.\'column\' = val OR \'test\'.\'other\' = bool', $query->getWhere());
    }
}