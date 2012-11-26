<?php
namespace Doctrine\Fun;

use Doctrine\ORM\EntityManagerDecorator as BaseEntityManagerDecorator;
use Doctrine\ORM\EntityManager;

class EntityManagerDecorator extends BaseEntityManagerDecorator
{
    public function __construct(EntityManager $em)
    {
        $this->wrapped = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($dql = '')
    {
        $query = parent::createQuery($dql);

        return new Query($query);
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedQuery($name)
    {
        $query = parent::createNamedQuery($name);

        return new Query($query);
    }
}
