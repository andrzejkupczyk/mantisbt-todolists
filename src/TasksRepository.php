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

        return array_map([$this, 'normalizeTask'], $this->fetch($query, [$bugId]));
    }

    public function insert(array $data): array
    {
        extract($this->prepareInput($data));

        $query = "INSERT INTO $this->table (bug_id, description) VALUES (" . db_param() . ', ' . db_param() . ')';

        if (!db_query($query, [$bug_id, $description])) {
            return [];
        }

        event_signal('EVENT_TODOLISTS_TASK_CREATED', [$data]);

        return $this->normalizeTask([
            'id' => db_insert_id($this->table),
            'bug_id' => $bug_id,
            'finished' => $finished,
            'description' => $description,
        ]);
    }

    /**
     * @return \IteratorAggregate|bool
     */
    public function update(array $data)
    {
        extract($this->prepareInput($data));

        $query = "UPDATE $this->table SET description = " . db_param() . ', finished = ' . db_param() . ' WHERE id = ' . db_param();

        if ($result = db_query($query, [$description, (int) $finished, $id])) {
            event_signal('EVENT_TODOLISTS_TASK_UPDATED', [$data]);
        }

        return $result;
    }

    protected function normalizeTask(array $task): array
    {
        $task['descriptionHtml'] = mention_format_text($task['description']);
        $task['finished'] = in_array($task['finished'], ['t', '1'], true);

        return $task;
    }

    private function prepareInput(array $input): array
    {
        $input['description'] = strip_tags($input['description']);
        $input['finished'] = $input['finished'] ?? false;

        return $input;
    }
}
