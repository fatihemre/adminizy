<?php

namespace Eva\Model;

use Eva\Entity\FlatEntity;
use Eva\Library\Database;

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

    public function fetchAll(int $apartment_id): array|false
    {
        $sth = $this->connection->prepare("
SELECT f.*,(SELECT count(r.id) FROM residents AS r WHERE flat_id=f.id AND r.deleted_at IS NULL) as count FROM flats AS f 
WHERE f.apartment_id=:apartment_id AND f.deleted_at is null");
        $sth->execute([
            'apartment_id' => $apartment_id
        ]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, FlatEntity::class);
        return $sth->fetchAll();
    }
}