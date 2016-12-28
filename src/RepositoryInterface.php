<?php

namespace ToDoLists;

/**
 * @author Andrzej Kupczyk
 */
interface RepositoryInterface
{
    /**
     * @param int $taskId
     */
    public function delete($taskId);

    /**
     * @param string $query
     * @param array  $params
     */
    public function fetch($query, array $params = []);

    /**
     * @param  array $data
     *
     * @return array|bool
     */
    public function insert($data);

    /**
     * @param array $data
     */
    public function update($data);
}
