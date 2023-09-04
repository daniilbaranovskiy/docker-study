<?php

namespace App\Extensions;

use Doctrine\ORM\QueryBuilder;

abstract class UserRelationExtension extends AbstractCurrentUserExtension
{
    /**
     * @param QueryBuilder $queryBuilder
     * @return void
     */
    public function buildQuery(QueryBuilder $queryBuilder)
    {
        $rootAlias = $queryBuilder->getRootAliases()[self::FIRST_ELEMENT_ARRAY];
        $queryBuilder
            ->andWhere($rootAlias . '.user =:user')
            ->setParameter('user', $this->tokenStorage->getToken()->getUser());


    }

}