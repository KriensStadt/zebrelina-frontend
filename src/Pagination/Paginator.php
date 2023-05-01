<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as BasePaginator;

class Paginator extends BasePaginator
{
    private int $page;
    private int $perPage;

    public function __construct(Query|QueryBuilder $query, bool $fetchJoinCollection, int $page, int $perPage)
    {
        $this->page = $page;
        $this->perPage = $perPage;

        parent::__construct($query, $fetchJoinCollection);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getCurrentPageStart(): int
    {
        return 1 + ($this->perPage * $this->page);
    }

    public function getCurrentPageEnd(): int
    {
        return min(\count($this), $this->perPage * ($this->page + 1));
    }

    public function hasNextPage(): bool
    {
        return \count($this) > ($this->page + 1) * $this->perPage;
    }

    public function hasPrevPage(): bool
    {
        return $this->page > 0;
    }

    public function getPages(): array
    {
        return range(0, (int) ((\count($this) - 1) / $this->perPage));
    }
}
