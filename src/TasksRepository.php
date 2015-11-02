<?php

namespace ToDoLists;

/**
 * @author Andrzej Kupczyk
 */
class TasksRepository extends Repository
{
    /**
     * @var string
     */
    protected $table;

    public function __construct()
    {
        $this->table = plugin_table('tasks');
    }

    /**
     * @param integer $taskId
     */
    public function delete($taskId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = " . db_param();
        return db_query_bound($query, [$taskId]);
    }

    /**
     * Finds list by bug id.
     *
     * @param  integer $bugId
     * @return array
     */
    public function findByBug($bugId)
    {
        $query = "SELECT * FROM {$this->table} WHERE bug_id = " . db_param();
        $result = $this->fetch($query, [$bugId]);
        return $this->castFinishedToBool($result);
    }

    /**
     * @param  array           $data
     * @return array|boolean
     */
    public function insert($data)
    {
        extract($data);
        $query = "INSERT INTO {$this->table} (bug_id, description) VALUES (" . db_param() . ', ' . db_param() . ')';
        if (!db_query_bound($query, [$bug_id, $description])) {
            return false;
        }
        return [
            'id' => db_insert_id($this->table),
            'bug_id' => $bug_id,
            'description' => $description,
            'finished' => false,
        ];
    }

    /**
     * @param array $data
     */
    public function update($data)
    {
        extract($data);
        $query = "UPDATE {$this->table} SET description = " . db_param() . ", finished = " . db_param() . ' WHERE id = ' . db_param();
        return db_query_bound($query, [$description, $finished, $id]);
    }

    /**
     * @param  array   $result
     * @return array
     */
    protected function castFinishedToBool(array $result = [])
    {
        return array_map(function ($task) {
            $task['finished'] = (bool) $task['finished'];
            return $task;
        }, $result);
    }
}
