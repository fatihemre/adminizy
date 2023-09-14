<?php

namespace Apteasy\Entity;

class ResidentEntity
{
    public int $id;
    public int $flat_id;
    public string $display_name;
    public string $phone;
    public string $email;
    public $created_at;
    public $updated_at;
    public $deleted_at;
}