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
            'language' => $entity->language,
            'theme' => $entity->theme,
            'is_mfa_enabled' => $entity->is_mfa_enabled,
            'user_id' => $entity->id
        ];

        if(!empty($entity->password)) {
            $password_string = ', password=:password';
            $bindings['password'] = password()->hash($entity->password);
        }

        $sth = $this->connection->prepare("UPDATE users SET 
                 display_name=:display_name, 
                 email=:email, 
                 phone=:phone, 
                 language=:language,
                 theme=:theme,
                 is_mfa_enabled=:is_mfa_enabled,
                 updated_at=NOW() {$password_string} WHERE id=:user_id");
        return $sth->execute($bindings);
    }

    public function saveSecretKey(int $id, string $secret_key): bool
    {
        $sth = $this->connection->prepare("UPDATE users SET mfa_secret_key=:secret WHERE id=:id");
        return $sth->execute(['secret' => $secret_key, 'id'=>$id]);
    }

    public function saveRecoveryCodes(int $id, string $recovery_codes): bool
    {
        $sth = $this->connection->prepare("UPDATE users SET mfa_recovery_codes=:secret WHERE id=:id");
        return $sth->execute(['secret' => $recovery_codes, 'id'=>$id]);
    }
}