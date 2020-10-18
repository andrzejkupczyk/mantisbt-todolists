<?php

namespace Mantis\ToDoLists;

class TasksRepository
{
    const TABLE_NAME = 'tasks';

    /**
     * @var string
     */
    protected $table;

    public function __construct()
    {
        $this->table = plugin_table(self::TABLE_NAME);
    }

    /**
     * @param int $taskId
     *
     * @return \IteratorAggregate|boolean
     */
    public function delete($taskId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = " . db_param();

        return db_query($query, [$taskId]);
    }

    /**
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function fetch($query, array $params = [])
    {
        $result = db_query($query, $params);

        if (db_num_rows($result) === 0) {
            return [];
        }

        return $result->GetArray();
    }

    /**
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
            'finished' => false,
            'description' => $description,
        ];
    }

    /**
     * @param array $data
     *
     * @return \IteratorAggregate|boolean
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
