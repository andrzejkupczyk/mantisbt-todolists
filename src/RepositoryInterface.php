<?php

namespace ToDoLists;

/**
 * @author Andrzej Kupczyk
 */
interface RepositoryInterface
{
    /**
     * @param integer $taskId
     */
    public function delete($taskId);

    /**
     * @param $query
     * @param array    $params
     */
    public function fetch($query, array $params = []);

    /**
     * @param  array           $data
     * @return array|boolean
     */
    public function insert($data);

    /**
     * @param array $data
     */
    public function update($data);
}
