<?php

namespace Apteasy\Model;

interface IModel
{
    public function fetch(int $id);
    public function fetchAll();
    public function insert(array $variables);
    public function update(int $id, array $variables);
    public function remove(int $id);
    public function removePermanently(int $id);
}