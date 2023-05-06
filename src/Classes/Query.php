<?php

namespace Classes;

use Exceptions\BadSQLException;

class Query {

    private Array $select;
    private String $from;
    private Array $where;
    private Array $group;
    private String $order;

    /**
     * @param String $from
     *    The table to select from.
     * @return Query
     * @throws BadSQLException
     */
    public static function create(String $from): Query {
        preg_match('/\w+/', $from, $matches);
        if (!isset($matches[0]) || $matches[0] != $from) {
            throw new BadSQLException('Invalid table name');
        }
        $query = new Query();
        $query->from = $from;
        return $query;
    }

    /**
     * @return String
     */
    public function getFrom(): string {
        return $this->from;
    }
}