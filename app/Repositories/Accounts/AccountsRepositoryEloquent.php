<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts;
use App\Repositories\BaseRepository;

class AccountsRepositoryEloquent extends BaseRepository implements AccountsRepositoryContract
{
    public function __construct(Accounts $accounts)
    {
        parent::__construct($accounts);
    }
}