<?php

namespace App\Core\Shared;

use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    protected function commitTransaction(): void
    {
        DB::commit();
    }

    protected function rollbackTransaction(): void
    {
        DB::rollBack();
    }

    protected function transaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
