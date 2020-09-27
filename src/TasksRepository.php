<?php

namespace ToDoLists;

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

        return db_query($query, [$taskId]);
    }

    /**
     * Finds list by bug id.
     *
     * @param int $bugId
     *
     * @return array
     */
    public function findByBug($bugId)
    {
        $query = "SELECT * FROM {$this->table} WHERE bug_id = " . db_param();
        $result = $this->fetch($query, [$bugId]);

        return $this->castFinishedToBool($result);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function insert($data)
    {
        extract($data);
        $query = "INSERT INTO {$this->table} (bug_id, description) VALUES (" . db_param() . ', ' . db_param() . ')';
        if (!db_query($query, [$bug_id, $description])) {
            return false;
        }

        return [
            'id' => db_insert_id($this->table),
            'bug_id' => $bug_id,
            'description' => $description,
        ];
    }

    /**
     * @param array $data
     */
    public function update($data)
    {
        extract($data);
        $query = "UPDATE {$this->table} SET description = " . db_param() . ", finished = " . db_param() . ' WHERE id = ' . db_param();

        return db_query($query, [$description, (int) $finished, $id]);
    }

    /**
     * @param array $result
     *
     * @return array
     */
    protected function castFinishedToBool(array $result = [])
    {
        return array_map(function ($task) {
            $task['finished'] = in_array($task['finished'], ['t', '1'], true);

            return $task;
        }, $result);
    }
}
