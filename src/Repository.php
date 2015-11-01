<?php

namespace ToDoLists;

/**
 * Repository base class.
 *
 * @author Andrzej Kupczyk
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @param integer $taskId
     */
    public function delete($taskId)
    {
        //
    }

    /**
     * Fetches given query.
     *
     * @param  string  $query
     * @param  array   $params
     * @return array
     */
    public function fetch($query, array $params = [])
    {
        $result = db_query_bound($query, $params);

        if (0 == db_num_rows($result)) {
            return [];
        }

        return $result->GetArray();
    }

    /**
     * @param  array           $data
     * @return array|boolean
     */
    public function insert($data)
    {
        //
    }

    /**
     * @param array $data
     */
    public function update($data)
    {
        //
    }
}
