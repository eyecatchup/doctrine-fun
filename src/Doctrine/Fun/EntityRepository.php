<?php
namespace Doctrine\Fun;

use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\DBAL\LockMode;
use PhpOption;

class EntityRepository extends BaseEntityRepository
{
    /**
     * @param mixed $id
     * @param int $lockMode
     * @param mixed $lockVersion
     * @return PhpOption\Option
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return PhpOption\LazyOption::create(array($this, '__phpOption_find_Callback'), array($id, $lockMode, $lockVersion));
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return PhpOption\Option
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return PhpOption\LazyOption::create(array($this, '__phpOption_findOneBy_Callback'), array($criteria, $orderBy));
    }

    /**
     * @internal
     * @param mixed $id
     * @param int $lockMode
     * @param mixed $lockVersion
     * @return PhpOption\Option
     */
    public function __phpOption_find_Callback($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        $entity = parent::find($id, $lockMode, $lockVersion);

        return $this->createOption($entity);
    }

    /**
     * @internal
     * @param array $criteria
     * @param array $orderBy
     * @return PhpOption\Option
     */
    public function __phpOption_findOneBy_Callback(array $criteria, array $orderBy = null)
    {
        $entity = parent::findOneBy($criteria, $orderBy);

        return $this->createOption($entity);
    }

    /**
     * Creates appropriate option object
     *
     * @param $entity
     * @return PhpOption\Option
     */
    protected function createOption($entity)
    {
        if ($entity === null) {
            return PhpOption\None::create();
        }

        return PhpOption\Some::create($entity);
    }
}