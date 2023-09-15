<?php

namespace Apteasy\Model;

use Apteasy\Entity\FlatEntity;
use Apteasy\Library\Database;

class Flat
{
    use Database;

    public function fetch(int $id): FlatEntity|false
    {
        $flat = $this->connection->prepare("
SELECT f.*, (SELECT count(r.id) FROM residents AS r WHERE flat_id=f.id AND r.deleted_at IS NULL) as count FROM flats AS f 
WHERE f.id=:flat_id AND f.deleted_at is null
");
        $flat->execute(['flat_id' => $id]);
        $flat->setFetchMode(\PDO::FETCH_CLASS, FlatEntity::class);
        return $flat->fetch();
    }

    public function fetchAll(int $building_id): array|false
    {
        $sth = $this->connection->prepare("
SELECT f.*,(SELECT count(r.id) FROM residents AS r WHERE flat_id=f.id AND r.deleted_at IS NULL) as count FROM flats AS f 
WHERE f.building_id=:building_id AND f.deleted_at is null");
        $sth->execute([
            'building_id' => $building_id
        ]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, FlatEntity::class);
        return $sth->fetchAll();
    }

    public function insert(FlatEntity $entity): int|false
    {
        $sth = $this->connection->prepare("INSERT INTO flats (building_id, display_name, amount) VALUES (:building_id, :display_name, :amount)");
        $sth->execute([
            'building_id' => $entity->building_id,
            'display_name' => $entity->display_name,
            'amount' => $entity->amount
        ]);
        return $this->connection->lastInsertId();
    }

    public function update(FlatEntity $entity): bool
    {
        $sth = $this->connection->prepare("UPDATE flats SET display_name=:display_name, amount=:amount, updated_at=NOW() WHERE id=:flat_id");
        return $sth->execute([
            'display_name' => $entity->display_name,
            'amount' => $entity->amount,
            'flat_id'=>$entity->id
        ]);
    }

    public function remove(int $id): bool
    {
        $sth = $this->connection->prepare("UPDATE flats SET updated_at = NOW(), deleted_at = NOW() WHERE id=:flat_id");
        return $sth->execute(['flat_id'=>$id]);
    }

    public function removePermanently(int $id): bool
    {
        return $this->connection->prepare("DELETE FROM flats WHERE id=:flat_id")->execute(['flat_id'=>$id]);
    }

    public function total()
    {
        $sth = $this->connection->query("SELECT count(id) as totalFlats, sum(amount) as totalAmount FROM flats WHERE 
                                deleted_at IS NULL AND 
                                building_id IN (SELECT id FROM buildings WHERE deleted_at IS NULL) ");
        $sth->setFetchMode(\PDO::FETCH_OBJ);
        return $sth->fetch();
    }
}