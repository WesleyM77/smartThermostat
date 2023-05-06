<?php

use Classes\Secrets;
use PHPUnit\Framework\TestCase;

class SecretsTest extends TestCase {

    /**
     * Test that we have demo credentials in secrets.example.json. This also tests that the Secrets class works.
     */
    public function testDatabaseCredentials(): void {
        Secrets::$useExampleFile = TRUE;
        $this->assertEquals('exampleSqlHost', Secrets::get('sqlHost'));
        $this->assertEquals('exampleSqlUser', Secrets::get('sqlUser'));
        $this->assertEquals('exampleSqlPass', Secrets::get('sqlPass'));
        $this->assertEquals('exampleSqlDatabase', Secrets::get('sqlDatabase'));
    }
}