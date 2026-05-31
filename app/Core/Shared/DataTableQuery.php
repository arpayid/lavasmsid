<?php

namespace App\Core\Shared;

use Illuminate\Database\Eloquent\Builder;

class DataTableQuery
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public static function make(Builder $query): self
    {
        return new self($query);
    }

    public function search(array $columns, ?string $value = null): self
    {
        if (empty($value)) {
            return $this;
        }

        return $this->where(function (Builder $q) use ($columns, $value) {
            foreach ($columns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, 'like', "%{$value}%");
                } else {
                    $q->orWhere($column, 'like', "%{$value}%");
                }
            }
        });
    }

    public function where(string $column, mixed $operator = null, mixed $value = null): self
    {
        if ($value === null) {
            $this->query->where($column, $operator);
        } else {
            $this->query->where($column, $operator, $value);
        }

        return $this;
    }

    public function orderBy(string $column, string $direction = 'desc'): self
    {
        $this->query->orderBy($column, $direction);

        return $this;
    }

    public function filter(string $column, mixed $value): self
    {
        if ($value !== null && $value !== '') {
            $this->query->where($column, $value);
        }

        return $this;
    }

    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    public function get(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query->get();
    }

    public function first(): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->query->first();
    }
}
