<?php

namespace Apteasy\Model;

use Apteasy\Entity\ResidentEntity;
use Apteasy\Library\Database;

class Resident
{
    use Database;

    public function fetch(int $id): ResidentEntity|false
    {
        $sth = $this->connection->prepare("SELECT * FROM residents WHERE id=:id");
        $sth->execute(['id' => $id]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, ResidentEntity::class);
        return $sth->fetch();
    }

    public function fetchAll(int $flat_id): array|false
    {
        $sth = $this->connection->prepare("SELECT * FROM residents WHERE flat_id=:flat_id AND deleted_at IS NULL");
        $sth->execute(['flat_id' => $flat_id]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, ResidentEntity::class);
        return $sth->rowCount() > 0 ? $sth->fetchAll() : false;
    }

    public function insert(ResidentEntity $entity): int|false
    {
        $sth = $this->connection->prepare("INSERT INTO residents (flat_id, display_name, phone, email) VALUES (:flat_id, :display_name, :phone, :email)");
        $sth->execute([
            'flat_id' => $entity->flat_id,
            'display_name' => $entity->display_name,
            'phone' => $entity->phone,
            'email' => $entity->email
        ]);
        return $this->connection->lastInsertId();
    }

    public function update(ResidentEntity $entity): bool
    {
        $sth = $this->connection->prepare("UPDATE residents SET display_name=:display_name, phone=:phone, email=:email, updated_at=NOW() WHERE id=:resident_id");
        return $sth->execute([
            'resident_id' => $entity->id,
            'display_name' => $entity->display_name,
            'phone' => $entity->phone,
            'email' => $entity->email
        ]);
    }

    public function remove(int $resident_id)
    {
        $sth = $this->connection->prepare("UPDATE residents SET updated_at = NOW(), deleted_at = NOW() WHERE id=:resident_id");
        return $sth->execute(['resident_id'=>$resident_id]);
    }

    public function removePermanently(int $resident_id): bool
    {
        return $this->connection->prepare("DELETE FROM residents WHERE id=:resident_id")->execute(['resident_id'=>$resident_id]);
    }

    public function total()
    {
        $sth = $this->connection->query("SELECT count(*) FROM residents WHERE deleted_at IS NULL AND flat_id IN (SELECT id FROM flats WHERE deleted_at IS NULL AND building_id IN (SELECT id FROM buildings WHERE deleted_at IS NULL))");
        return $sth->fetchColumn();
    }
}