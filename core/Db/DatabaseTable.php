<?php

namespace Core\Db;

use Hindbiswas\QueBee\Query;
use Hindbiswas\QueBee\Table\CreateTable;

class DatabaseTable
{
    private $paginate = false;
    private $page = 1;
    protected string $pk;

    public function __construct(public readonly \mysqli $conn, public readonly CreateTable $table)
    {
        $this->pk = $table->get_pk();
    }

    public function set_pagination(int $page = 1)
    {
        $this->paginate = true;
        $this->page = $page;
    }

    public function run($query)
    {
        return $this->conn->query($query);
    }

    public function insert(array $values = [])
    {
        $parsed_values = array_filter($values, function ($value, $key) {
            return ($this->table->hasColumn($key));
        }, ARRAY_FILTER_USE_BOTH);
        foreach ($parsed_values as $key => $value) {
            $parsed_values[$key] = $this->conn->real_escape_string($value);
        }

        $query = Query::insert($parsed_values)
            ->into($this->table->name)
            ->build();

        if (!$this->conn->query($query)) return False;
        return $this->get_last_entry($this->table->get_pk());
    }

    public function update(array $values, string $conditions)
    {
        if (!$this->entry_exists($conditions)) return False;

        $parsed_values = array_filter($values, function ($value, $key) {
            return ($this->table->hasColumn($key));
        }, ARRAY_FILTER_USE_BOTH);
        foreach ($parsed_values as $key => $value) {
            $parsed_values[$key] = $this->conn->real_escape_string($value);
        }
        
        $query = Query::update($this->table->name)
            ->set($parsed_values)
            ->whereClause($conditions)
            ->build();
        if (!$this->conn->query($query)) return False;
        return True;
    }

    public function delete(string $conditions)
    {
        if (!$this->entry_exists($conditions)) return False;
        $query = Query::delete($this->table->name)
            ->whereClause($conditions)
            ->build();

        if (!$this->conn->query($query)) return False;
        return True;
    }

    public function get_entry_by_key($value, string $key = 'id', array $selectors = ['*'])
    {
        $query = Query::select($selectors)->from($this->table->name)->where($key, '=', $value)->build();
        return $this->get_entry($query);
    }

    public function get_entry_by_condition(string $conditions, array $selectors = ['*'])
    {
        $query = Query::select($selectors)
            ->from($this->table->name)
            ->whereClause($conditions)
            ->build();
        return $this->get_entry($query);
    }

    public function get_count_total(): int
    {
        $query = Query::select(["count" => "COUNT($this->pk)"])
            ->from($this->table->name)
            ->build();
        return intval($this->get_entry($query)['count']);
    }

    public function get_count(string $conditions): int
    {
        $result = $this->get_entry_by_condition($conditions, ["count" => "COUNT($this->pk)"]);
        return intval($result['count']);
    }

    public function get_entry_list(?string $conditions = null, $limit = 50, array $selectors = ['*'], array $order_by = ['id', 'desc'])
    {
        $query = Query::select($selectors)
            ->from($this->table->name)
            ->orderBy(...$order_by);
        if ($conditions) $query = $query->whereClause($conditions);

        if ($this->paginate && $limit) {
            $query = $query->limit($limit, ($this->page * $limit) - $limit);
        } elseif ($limit) $query = $query->limit($limit);

        $query = $query->build();
        $result = $this->get_entry($query, true);

        if (!$this->paginate) return $result;
        $total_count = ($conditions) ? $this->get_count($conditions) : $this->get_count_total();
        $current_count = (is_array($result)) ? count($result) : 0;

        $limit = ($limit) ? $limit : $total_count;
        $total_pages = ($total_count) ? intval(ceil($total_count / $limit)) : 1;
        $prev_page = ($this->page > 1) ? $this->page - 1 : null;
        $next_page = ($this->page < $total_pages) ? $this->page + 1 : null;

        return [
            $result,
            [
                'page_prev' => $prev_page,
                'page_next' => $next_page,
                'page_total' => $total_pages,
                'page_current' => $this->page,
                'total_entries' => $total_count,
                'current_entries' => $current_count,
            ],
        ];
    }

    public function get_last_entry(string $key = 'id', array $selectors = ['*'])
    {
        $query = Query::select($selectors)->from($this->table->name)->orderBy($key, 'desc')->limit(1)->build();
        $entry = mysqli_fetch_assoc(mysqli_query($this->conn, $query));
        return $entry;
    }

    public function entry_exists(string $conditions)
    {
        $query = Query::select([$this->table->get_pk()])->from($this->table->name)->whereClause($conditions)->limit(1)->build();
        return ($this->get_entry($query)) ? true : false;
    }

    protected function get_entry($query, $multiple = false)
    {
        if ($multiple) {
            $result = [];

            if ($queried_result = mysqli_query($this->conn, $query)) {
                while ($fetched = mysqli_fetch_assoc($queried_result)) {
                    array_push($result, $fetched);
                }
            }

            return $result;
        }
        return mysqli_fetch_assoc(mysqli_query($this->conn, $query));
    }

    protected function get_condition_clause(string $conditions)
    {
        return str_replace(["&&", "||", " !! ", " ?? "], ["AND", "OR", " NOT ", " LIKE "], $conditions);
    }

    public function flush($proceed = false)
    {
        if ($proceed) {
            $this->conn->query("SET FOREIGN_KEY_CHECKS = 0;");
            $this->conn->query("DROP TABLE IF EXISTS `" . $this->table->name . "`;");
            $this->conn->query("SET FOREIGN_KEY_CHECKS = 1;");
        }
    }
}
