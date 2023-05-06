<?php

namespace Classes;

use Exceptions\BadSQLException;

class Query {

    // Regex to prevent some bad characters that might allow SQL injection.
    private const BAD_CHARS_REGEX = '/[,;\\#]/';
    // Regex for making sure a table name or alias is just that, and not an attempt at SQL injection.
    private const TABLE_ALIAS_REGEX = '/\w+/';
    // The special character to indicate a query parameter.
    private const QUERY_PARAMETER_CHARACTER = ':';

    private array $select;
    private string $from;
    private string $where;
    private array $group;
    private string $order;

    /**
     * Creates the query and sets the from table.
     * @param string $from
     *    The table to select from.
     * @param string $alias
     * @return Query
     * @throws BadSQLException
     */
    public static function create(string $from, string $alias = ''): Query {
        $from = trim($from);
        $alias = trim($alias);

        // May need to change this to support sub-queries.
        preg_match(self::TABLE_ALIAS_REGEX, $from, $matches);
        if (!isset($matches[0]) || $matches[0] != $from) {
            throw new BadSQLException('Invalid table name');
        }
        $query = new Query();
        $query->from = self::escape($from);

        // Sub-query support will not need this updated.
        if ($alias != '') {
            preg_match(self::TABLE_ALIAS_REGEX, $alias, $matches);
            if (!isset($matches[0]) || $matches[0] != $alias) {
                throw new BadSQLException('Invalid alias name');
            }
            $query->from .= ' ' . self::escape($alias);
        }

        return $query;
    }

    /**
     * Sets/adds a select on the query and returns the query, so it can be chained.
     *
     * @param string|array $select
     * @return $this
     * @throws BadSQLException
     */
    public function select(string|array $select): Query {
        if (!is_array($select)) {
            $select = [$select];
        }
        foreach($select as $key => $item) {
            $item = trim($item);
            $matches = [];
            preg_match(self::BAD_CHARS_REGEX, $item, $matches);
            if (!empty($matches)) {
                throw new BadSQLException('Invalid select statement');
            }
            $select[$key] = self::escape($item);
        }
        if (isset($this->select)) {
            $this->select = array_merge($this->select, $select);
        }
        else {
            $this->select = $select;
        }
        return $this;
    }

    /**
     * The first condition of the query.
     *
     * @param string $where
     * @param array $params
     * @return $this
     */
    public function where(string $where, array $params = []): Query {
        $where = trim($where);
        $where = self::replaceParams($where, $params);
        $this->where = self::escapeWhere($where);

        return $this;
    }

    /**
     * Any subsequent AND condition of the query.
     *
     * @param string $where
     * @param array $params
     * @return $this
     */
    public function andWhere(string $where, array $params = []): Query {
        $where = trim($where);
        $where = self::replaceParams($where, $params);
        $this->where .= ' AND ' . self::escapeWhere($where);

        return $this;
    }

    /**
     * Any subsequent OR condition of the query.
     *
     * @param string $where
     * @param array $params
     * @return $this
     */
    public function orWhere(string $where, array $params = []): Query {
        $where = trim($where);
        $where = self::replaceParams($where, $params);
        $this->where .= ' OR ' . self::escapeWhere($where);

        return $this;
    }

    /**
     * Replaces params into the string. Params must start with the query parameter character.
     *
     * @param string $string
     * @param array $params
     * @return string
     */
    private static function replaceParams(string $string, array $params): string {
        foreach($params as $param => $value) {
            if (substr($param, 0, 1) == self::QUERY_PARAMETER_CHARACTER) {
                $string = str_replace($param, $value, $string);
            }
        }
        return $string;
    }

    /**
     * Escapes any table name or alias
     *
     * @param string $string
     * @return string
     */
    private static function escape(string $string): string {
        return preg_replace('/\w+/', '\'$0\'', $string);
    }

    /**
     * Escapes any table name or alias in a where string.
     *
     * @param string $where
     * @return string
     */
    private static function escapeWhere(string $where): string {
        $conditions = [
            '>=',
            '<=',
            '<>',
            '=',
            '>',
            '<',
            'BETWEEN',
            'LIKE',
            'IN',
        ];
        foreach ($conditions as $condition) {
            $conditionPos = stripos($where, $condition);
            if ($conditionPos !== FALSE) {
                $table = substr($where, 0, $conditionPos);
                $rest = substr($where, $conditionPos);
                $table = self::escape($table);
                return $table . $rest;
            }
        }
        return $where;
    }

    /**
     * @return string
     */
    public function getFrom(): string {
        return $this->from;
    }

    /**
     * Returns the select value as a string (how it will be in the query).
     *
     * @return string
     */
    public function getSelect(): string {
        return implode(', ', $this->select);
    }

    /**
     * @return string
     */
    public function getWhere(): string {
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