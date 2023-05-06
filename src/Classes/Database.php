<?php

namespace Classes;

/**
 * This is our database connector object.
 */
class Database {

    private $connection;

    function __construct() {
        $this->connect();
    }

    // connect function to establish connection and save it
    private function connect(): void {
        // TODO: This
        try {
            file_get_contents('secrets.json');
        } catch (Exception $e) {

        }
    }

}