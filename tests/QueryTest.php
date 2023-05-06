<?php

use Classes\Query;
use Exceptions\BadSQLException;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase {

    // Test that we can actually create a query with a valid table name.
    public function testCreateQuery(): void {
        $query = Query::create('test');
        $this->assertEquals('test', $query->getFrom());
    }

    // Test that we won't allow non-allowed characters for table names.
    public function testCreateQueryBadTableName(): void {
        $this->expectException(BadSQLException::class);
        Query::create('test; select * from user where true;');
    }
}