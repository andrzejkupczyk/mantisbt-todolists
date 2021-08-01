<?php

declare(strict_types=1);

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
     * @return \IteratorAggregate|bool
     */
    public function delete(int $taskId)
    {
        $query = "DELETE FROM $this->table WHERE id = " . db_param();

        return db_query($query, [$taskId]);
    }

    /**
     * @return \IteratorAggregate|bool
     */
    public function deleteAssociatedToBug(int $bugId)
    {
        $query = "DELETE FROM $this->table WHERE bug_id = " . db_param();

        return db_query($query, [$bugId]);
    }

    public function fetch(string $query, array $params = []): array
    {
        $result = db_query($query, $params);

        if (db_num_rows($result) === 0) {
            return [];
        }

        return $result->GetArray();
    }

    /**
     * @return array|null
     */
    public function findById(int $taskId)
    {
        $results = $this->fetch("SELECT * FROM $this->table WHERE id = " . db_param(), [$taskId]);

        return $results ? $this->normalizeTask($results[0]) : null;
    }

    public function findByBug(int $bugId): array
    {
        $query = "SELECT * FROM $this->table WHERE bug_id = " . db_param();
        $results = $this->fetch($query, [$bugId]);

        return array_map([$this, 'normalizeTask'], $results);
    }

    public function insert(array $data): array
    {
        $data['description'] = strip_tags($data['description']);
        extract($data);

        $query = "INSERT INTO $this->table (bug_id, description) VALUES (" . db_param() . ', ' . db_param() . ')';
        if (!db_query($query, [$bug_id, $description])) {
            return [];
        }

        return $this->normalizeTask([
            'id' => db_insert_id($this->table),
            'bug_id' => $bug_id,
            'finished' => false,
            'description' => $description,
        ]);
    }

    /**
     * @return \IteratorAggregate|boolean
     */
    public function update(array $data)
    {
        extract($data);
        $query = "UPDATE $this->table SET description = " . db_param() . ", finished = " . db_param() . ' WHERE id = ' . db_param();

        return db_query($query, [strip_tags($description), (int)$finished, $id]);
    }

    protected function normalizeTask(array $task): array
    {
        $task['descriptionHtml'] = mention_format_text($task['description']);
        $task['finished'] = in_array($task['finished'], ['t', '1'], true);

        return $task;
    }
}
