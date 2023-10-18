<?php

namespace Apteasy\Entity;

class UserEntity
{
    public $id;
    public $display_name;
    public $email;
    public $password;
    public $phone;
    public $language;
    public $theme;
    public $is_mfa_enabled;
    public $mfa_secret_key;
    public $mfa_recovery_codes;
    public $created_at;
    public $updated_at;
    public $deleted_at;
}