<?php

namespace Eva\Entity;

class FlatEntity
{
    public int $id;
    public int $apartment_id;
    public string $display_name;
    public float $amount;
    public string $created_at;
    public string|null $updated_at;
    public string|null $deleted_at;
}