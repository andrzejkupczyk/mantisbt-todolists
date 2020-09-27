<?php

namespace ToDoLists;

abstract class Repository
{
    /**
     * Fetches given query.
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function fetch($query, array $params = [])
    {
        $result = db_query($query, $params);

        if (0 == db_num_rows($result)) {
            return [];
        }

        return $result->GetArray();
    }

    /**
     * @param int $taskId
     */
    abstract public function delete($taskId);

    /**
     * @param array $data
     *
     * @return array|bool
     */
    abstract public function insert($data);

    /**
     * @param array $data
     */
    abstract public function update($data);
}
