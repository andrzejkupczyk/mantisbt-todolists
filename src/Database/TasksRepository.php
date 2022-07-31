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
     * @param int $taskId
     */
    public function delete($taskId)
    {
        $query = "DELETE FROM $this->table WHERE id = " . db_param();

        return db_query($query, [$taskId]);
    }

    /**
     * @return bool|\IteratorAggregate
     * @param int $bugId
     */
    public function deleteAssociatedToBug($bugId)
    {
        $query = "DELETE FROM $this->table WHERE bug_id = " . db_param();

        return db_query($query, [$bugId]);
    }

    /**
     * @param string $query
     * @param mixed[] $params
     */
    public function fetch($query, $params = []): array
    {
        $result = db_query($query, $params);

        if (db_num_rows($result) === 0) {
            return [];
        }

        return $result->GetArray();
    }

    /**
     * @return null|array
     * @param int $taskId
     */
    public function findById($taskId)
    {
        $results = $this->fetch("SELECT * FROM $this->table WHERE id = " . db_param() . ' LIMIT 1', [$taskId]);

        return $results ? $this->normalizeTask($results[0]) : null;
    }

    /**
     * @param int $bugId
     */
    public function findByBug($bugId): array
    {
        $query = "SELECT * FROM $this->table WHERE bug_id = " . db_param() . ' ORDER BY id';

        return array_map([$this, 'normalizeTask'], $this->fetch($query, [$bugId]));
    }

    /**
     * @param mixed[] $data
     */
    public function insert($data): array
    {
        $prepareInput = $this->prepareInput($data);
        extract($prepareInput);

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

    /**
     * @param mixed[] $data
     */
    public function update($data): array
    {
        $finished = null;
        $id = null;
        $prepareInput = $this->prepareInput($data);
        extract($prepareInput);

        $query = "UPDATE $this->table SET description = " . db_param() . ', finished = ' . db_param() . ' WHERE id = ' . db_param();
        db_query($query, [$description, (int) $finished, $id]);

        $task = $this->findById((int) $id);

        if (db_affected_rows()) {
            event_signal('EVENT_TODOLISTS_TASK_UPDATED', [$task]);
        }

        return $task;
    }

    /**
     * @param mixed[] $task
     */
    protected function normalizeTask($task): array
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
