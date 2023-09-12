<?php

namespace Eva\Model;

use Eva\Entity\BuildingEntity;
use Eva\Library\Database;

class Building implements IModel
{
    use Database;
    
    public function fetch(int $building_id): BuildingEntity|false
    {
        $sth = $this->connection->prepare("
SELECT b.*, (SELECT count(f.id) FROM flats AS f WHERE f.building_id=b.id AND f.deleted_at IS NULL) as count FROM buildings AS b WHERE b.id = :building_id");
        $sth->execute(['building_id' => $building_id]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, BuildingEntity::class);
        return $sth->fetch();
    }

    public function fetchAll(): array|false
    {
        $sth = $this->connection->query("SELECT * FROM buildings WHERE deleted_at is null");
        $sth->setFetchMode(\PDO::FETCH_CLASS, BuildingEntity::class);
        return $sth->fetchAll();
    }

    public function insert(array $variables): int|false
    {
        $sth =$this->connection->prepare("INSERT INTO buildings (display_name, address) VALUES (:display_name, :address)");
        $sth->execute([
            'display_name' => $variables['display_name'],
            'address' => $variables['address']
        ]);
        return $this->connection->lastInsertId();
    }

    public function update(int $building_id, array $variables): bool
    {
        $sth = $this->connection->prepare("UPDATE buildings SET display_name=:display_name, address=:address, updated_at=NOW() WHERE id=:building_id");
        return $sth->execute([
            'display_name' => $variables['display_name'],
            'address' => $variables['address'],
            'building_id'=>$building_id
        ]);
    }

    public function remove(int $building_id): bool
    {
        $sth = $this->connection->prepare("UPDATE buildings SET updated_at = NOW(), deleted_at = NOW() WHERE id=:building_id");
        return $sth->execute(['building_id'=>$building_id]);
    }

    public function removePermanently(int $building_id): bool
    {
        $sth = $this->connection->prepare("DELETE FROM buildings WHERE id=:building_id");
        return $sth->execute(['building_id'=>$building_id]);
    }
}