<?php

namespace Apteasy\Model;

use Apteasy\Entity\BuildingEntity;
use Apteasy\Library\Database;

class Building
{
    use Database;

    public function fetch(int $id): BuildingEntity|false
    {
        $sth = $this->connection->prepare("
SELECT b.*, (SELECT count(f.id) FROM flats AS f WHERE f.building_id=b.id AND f.deleted_at IS NULL) as count FROM buildings AS b WHERE b.id = :building_id");
        $sth->execute(['building_id' => $id]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, BuildingEntity::class);
        return $sth->fetch();
    }

    public function fetchAll(): array|false
    {
        $sth = $this->connection->query("SELECT * FROM buildings WHERE deleted_at is null");
        $sth->setFetchMode(\PDO::FETCH_CLASS, BuildingEntity::class);
        return $sth->fetchAll();
    }

    public function insert(BuildingEntity $entity): int|false
    {
        $sth =$this->connection->prepare("INSERT INTO buildings (display_name, address) VALUES (:display_name, :address)");
        $sth->execute([
            'display_name' => $entity->display_name,
            'address' => $entity->address
        ]);
        return $this->connection->lastInsertId();
    }

    public function update(BuildingEntity $entity): bool
    {
        $sth = $this->connection->prepare("UPDATE buildings SET display_name=:display_name, address=:address, updated_at=NOW() WHERE id=:building_id");
        return $sth->execute([
            'display_name' => $entity->display_name,
            'address' => $entity->address,
            'building_id'=>$entity->id
        ]);
    }

    public function remove(int $id): bool
    {
        $sth = $this->connection->prepare("UPDATE buildings SET updated_at = NOW(), deleted_at = NOW() WHERE id=:building_id");
        return $sth->execute(['building_id'=>$id]);
    }

    public function removePermanently(int $id): bool
    {
        return $this->connection->prepare("DELETE FROM buildings WHERE id=:building_id")->execute(['building_id'=>$id]);
    }
}