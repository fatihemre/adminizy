<?php

namespace Eva\Model;

use Eva\Entity\ApartmentEntity;
use Eva\Library\Database;

class Apartment
{
    use Database;

    public function fetch(int $apartment_id): ApartmentEntity|false
    {
        $sth = $this->connection->prepare("SELECT * FROM apartments WHERE id = :apartment_id");
        $sth->execute(['apartment_id' => $apartment_id]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, ApartmentEntity::class);
        return $sth->fetch();
    }
    public function fetchAll(): array|false
    {
        $sth = $this->connection->query("SELECT * FROM apartments WHERE deleted_at is null");
        $sth->setFetchMode(\PDO::FETCH_CLASS, ApartmentEntity::class);
        return $sth->fetchAll();
    }

    public function insert(array $variables): int|false
    {
        $sth =$this->connection->prepare("INSERT INTO apartments (display_name, address) VALUES (:display_name, :address)");
        $sth->execute([
            'display_name' => $variables['display_name'],
            'address' => $variables['address']
        ]);
        return $this->connection->lastInsertId();
    }

    public function update(int $id, array $variables): bool
    {
        $sth = $this->connection->prepare("UPDATE apartments SET display_name=:display_name, address=:address, updated_at=NOW() WHERE id=:id");
        return $sth->execute([
            'display_name' => $variables['display_name'],
            'address' => $variables['address'],
            'id'=>$id
        ]);
    }

    public function remove(int $id): bool
    {
        $sth = $this->connection->prepare("UPDATE apartments SET updated_at = NOW(), deleted_at = NOW() WHERE id=:id");
        return $sth->execute(['id'=>$id]);
    }

    public function removePermanently(int $id): bool
    {
        $sth = $this->connection->prepare("DELETE FROM apartments WHERE id=:id");
        return $sth->execute(['id'=>$id]);
    }
}