<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorFactory
{
    private RequestStack $requestStack;
    private ParameterBagInterface $parameters;

    public function __construct(RequestStack $requestStack, ParameterBagInterface $parameters)
    {
        $this->requestStack = $requestStack;
        $this->parameters = $parameters;
    }

    public function create(Query|QueryBuilder $query, bool $fetchJoinCollection = true): Paginator
    {
        $page = (int) $this->requestStack->getMainRequest()?->query?->get('page', null);
        $perPage = $this->parameters->get('paginator.entries_per_page');

        $query->setFirstResult($page * $perPage);
        $query->setMaxResults($perPage);

        return new Paginator($query, $fetchJoinCollection, $page, $perPage);
    }
}
