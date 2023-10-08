<?php

namespace Apteasy\Model;

use Apteasy\Entity\UserEntity;
use Apteasy\Library\Database;

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

    public function updateExists($email, $user_id): UserEntity|false
    {
        $sth = $this->connection->prepare("SELECT * FROM users WHERE email=:new_email and id<>:user_id");
        $sth->execute(['new_email' => $email, 'user_id' => $user_id]);
        $sth->setFetchMode(\PDO::FETCH_CLASS, UserEntity::class);
        return $sth->fetch();
    }

    public function update(UserEntity $entity): bool
    {
        $password_string = '';
        $bindings = [
            'display_name' => $entity->display_name,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'user_id' => $entity->id
        ];
        
        if(!empty($entity->password)) {
            $password_string = ', password=:password';
            $bindings['password'] = password()->hash($entity->password);
        }

        $sth = $this->connection->prepare("UPDATE users SET display_name=:display_name, email=:email, phone=:phone, updated_at=NOW() {$password_string} WHERE id=:user_id");
        return $sth->execute($bindings);
    }
}