<?php

declare(strict_types=1);

namespace WebGarden\ToDoLists\Database;

use MantisMarkdown;

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
     * @return bool|\IteratorAggregate
     */
    public function delete(string $taskId)
    {
        $query = "DELETE FROM $this->table WHERE id = " . db_param();

        return db_query($query, [$taskId]);
    }

    /**
     * @return bool|\IteratorAggregate
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
     * @return null|array
     */
    public function findById(int $taskId)
    {
        $results = $this->fetch("SELECT * FROM $this->table WHERE id = " . db_param() . ' LIMIT 1', [$taskId]);

        return $results ? $this->normalizeTask($results[0]) : null;
    }

    public function findByBug(int $bugId): array
    {
        $query = "SELECT * FROM $this->table WHERE bug_id = " . db_param() . ' ORDER BY id';

        return array_map([$this, 'normalizeTask'], $this->fetch($query, [$bugId]));
    }

    public function insert(array $data): array
    {
        $input = $this->prepareInput($data);

        $query = "INSERT INTO $this->table (bug_id, description) VALUES (" . db_param() . ', ' . db_param() . ')';

        db_query($query, [$input['bug_id'], $input['description']]);

        if (!$taskId = db_insert_id($this->table)) {
            return [];
        }

        $task = $this->normalizeTask([
            'id' => $taskId,
            'bug_id' => $input['bug_id'],
            'finished' => $input['finished'],
            'description' => $input['description'],
        ]);

        event_signal('EVENT_TODOLISTS_TASK_CREATED', [$task]);

        return $task;
    }

    /**
     * @return void
     */
    public function update(array $data)
    {
        $input = $this->prepareInput($data);

        $query = "UPDATE $this->table SET description = " . db_param() . ', finished = ' . db_param() . ' WHERE id = ' . db_param();
        db_query($query, [$input['description'], (int) $input['finished'], $input['id']]);

        $task = $this->findById((int) $input['id']);

        if (db_affected_rows()) {
            event_signal('EVENT_TODOLISTS_TASK_UPDATED', [$task]);
        }
    }

    protected function normalizeTask(array $task): array
    {
        $task['id'] = (int) $task['id'];
        $task['bug_id'] = (int) $task['bug_id'];
        $task['finished'] = in_array($task['finished'], ['t', '1']);
        $task['descriptionHtml'] = mention_format_text(
            MantisMarkdown::convert_line($task['description'])
        );

        return $task;
    }

    /**
     * @return array{id: string, bug_id: int, description: string, finished: bool}
     */
    private function prepareInput(array $input): array
    {
        if (array_key_exists('bug_id', $input)) {
            $input['bug_id'] = (int) $input['bug_id'];
        }
        $input['description'] = strip_tags($input['description']);
        $input['finished'] = filter_var($input['finished'] ?? false, FILTER_VALIDATE_BOOLEAN);

        return $input;
    }
}
