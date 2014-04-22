<?php

/**
 *
 * @author Daniel
 */
interface IGenericDao {
    public function insert($model);
    public function update($model);
    public function delete($id);
}
