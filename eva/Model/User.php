<?php

namespace Eva\Model;

use Eva\Entity\UserEntity;
use Eva\Library\Database;

class User
{
    use Database;

    public function fetch($email): UserEntity|false
    {
        $sth = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $sth->execute(['email' => $email]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, UserEntity::class);
        return $sth->fetch();
    }
}