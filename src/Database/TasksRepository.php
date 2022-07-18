<?php

declare(strict_types=1);

namespace Mantis\ToDoLists\Database;

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
    public function delete(int $taskId)
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
        extract($this->prepareInput($data));

        $query = "INSERT INTO $this->table (bug_id, description) VALUES (" . db_param() . ', ' . db_param() . ')';

        db_query($query, [$bug_id, $description]);

        if (!$taskId = db_insert_id($this->table)) {
            return [];
        }

        $task = $this->normalizeTask([
            'id' => $taskId,
            'bug_id' => $bug_id,
            'finished' => $finished,
            'description' => $description,
        ]);

        event_signal('EVENT_TODOLISTS_TASK_CREATED', [$task]);

        return $task;
    }

    public function update(array $data): array
    {
        extract($this->prepareInput($data));

        $query = "UPDATE $this->table SET description = " . db_param() . ', finished = ' . db_param() . ' WHERE id = ' . db_param();
        db_query($query, [$description, (int) $finished, $id]);

        $task = $this->findById((int) $id);

        if (db_affected_rows()) {
            event_signal('EVENT_TODOLISTS_TASK_UPDATED', [$task]);
        }

        return $task;
    }

    protected function normalizeTask(array $task): array
    {
        $task['id'] = (int) $task['id'];
        $task['bug_id'] = (int) $task['bug_id'];
        $task['finished'] = in_array($task['finished'], ['t', '1'], true);
        $task['descriptionHtml'] = mention_format_text(
            MantisMarkdown::convert_line($task['description'])
        );

        return $task;
    }

    private function prepareInput(array $input): array
    {
        if (array_key_exists('bug_id', $input)) {
            $input['bug_id'] = (int) $input['bug_id'];
        }
        $input['description'] = strip_tags($input['description']);
        $input['finished'] = $input['finished'] ?? false;

        return $input;
    }
}