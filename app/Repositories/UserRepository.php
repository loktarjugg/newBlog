<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 2017/8/16
 * Time: 10:04
 */

namespace App\Repositories;


use App\Models\User;
use App\Traits\BaseRepository;

class UserRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

}