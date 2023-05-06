<?php

namespace Classes;

use Exceptions\BadSQLException;

class Query {

    private array $select;
    private string $from;
    private array $where;
    private array $group;
    private string $order;

    /**
     * Creates the query and sets the from table.
     * @param string $from
     *    The table to select from.
     * @return Query
     * @throws BadSQLException
     */
    public static function create(string $from): Query {
        preg_match('/\w+/', $from, $matches);
        // May need to change this to support sub-queries.
        if (!isset($matches[0]) || $matches[0] != $from) {
            throw new BadSQLException('Invalid table name');
        }
        $query = new Query();
        $query->from = $from;
        return $query;
    }

    /**
     * @return string
     */
    public function getFrom(): string {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getSelect(): array {
        return $this->select;
    }

    /**
     * @return array
     */
    public function getWhere(): array {
        return $this->where;
    }

    /**
     * @return array
     */
    public function getGroup(): array {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getOrder(): string {
        return $this->order;
    }
}