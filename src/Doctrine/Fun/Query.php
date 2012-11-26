<?php
namespace Doctrine\Fun;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\ORM\AbstractQuery;
use PhpOption\Option;

final class Query extends AbstractQuery
{
    private $wrapped;

    public function __construct(AbstractQuery $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    public function getWrappedQuery()
    {
        return $this->wrapped;
    }

    public function getOptionResult($hydrationMode = null)
    {
        return Option::fromReturn(array($this, 'getOneOrNullResult'), array($hydrationMode));
    }

    public function getOneOrNullResult($hydrationMode = null)
    {
        return $this->wrapped->getOneOrNullResult($hydrationMode);
    }

    public function getEntityManager()
    {
        return $this->wrapped->getEntityManager();
    }

    public function getParameters()
    {
        return $this->wrapped->getParameters();
    }

    public function getParameter($key)
    {
        return $this->wrapped->getParameter($key);
    }

    public function setParameters($parameters)
    {
        return $this->wrapped->setParameters($parameters);
    }

    public function setParameter($key, $value, $type = null)
    {
        return $this->wrapped->setParameter($key, $value, $type);
    }

    public function processParameterValue($value)
    {
        return $this->wrapped->processParameterValue($value);
    }

    public function setResultSetMapping(ResultSetMapping $rsm)
    {
        return $this->wrapped->setResultSetMapping($rsm);
    }

    public function setHydrationCacheProfile(QueryCacheProfile $profile = null)
    {
        return $this->wrapped->setHydrationCacheProfile($profile);
    }

    public function getHydrationCacheProfile()
    {
        return $this->wrapped->getHydrationCacheProfile();
    }

    public function setResultCacheProfile(QueryCacheProfile $profile = null)
    {
        return $this->wrapped->setResultCacheProfile($profile);
    }

    public function setResultCacheDriver($resultCacheDriver = null)
    {
        return $this->wrapped->setResultCacheDriver($resultCacheDriver);
    }

    public function getResultCacheDriver()
    {
        return $this->wrapped->getResultCacheDriver();
    }

    public function useResultCache($bool, $lifetime = null, $resultCacheId = null)
    {
        return $this->wrapped->useResultCache($bool, $lifetime, $resultCacheId);
    }

    public function setResultCacheLifetime($lifetime)
    {
        return $this->wrapped->setResultCacheLifetime($lifetime);
    }

    public function getResultCacheLifetime()
    {
        return $this->wrapped->getResultCacheLifetime();
    }

    public function expireResultCache($expire = true)
    {
        return $this->wrapped->expireResultCache($expire);
    }

    public function getExpireResultCache()
    {
        return $this->wrapped->getExpireResultCache();
    }

    public function getQueryCacheProfile()
    {
        return $this->wrapped->getQueryCacheProfile();
    }

    public function setFetchMode($class, $assocName, $fetchMode)
    {
        return $this->wrapped->setFetchMode($class, $assocName, $fetchMode);
    }

    public function setHydrationMode($hydrationMode)
    {
        return $this->wrapped->setHydrationMode($hydrationMode);
    }

    public function getHydrationMode()
    {
        return $this->wrapped->getHydrationMode();
    }

    public function getResult($hydrationMode = ORMQuery::HYDRATE_OBJECT)
    {
        return $this->wrapped->getResult($hydrationMode);
    }

    public function getArrayResult()
    {
        return $this->wrapped->getArrayResult();
    }

    public function getScalarResult()
    {
        return $this->wrapped->getScalarResult();
    }


    public function getSingleResult($hydrationMode = null)
    {
        return $this->wrapped->getSingleResult($hydrationMode);
    }

    public function getSingleScalarResult()
    {
        return $this->wrapped->getSingleScalarResult();
    }

    public function setHint($name, $value)
    {
        return $this->wrapped->setHint($name, $value);
    }

    public function getHint($name)
    {
        return $this->wrapped->getHint($name);
    }

    public function getHints()
    {
        return $this->wrapped->getHints();
    }

    public function execute($parameters = null, $hydrationMode = null)
    {
        return $this->wrapped->execute($parameters, $hydrationMode);
    }

    public function setResultCacheId($id)
    {
        return $this->wrapped->setResultCacheId($id);
    }

    public function getResultCacheId()
    {
        return $this->wrapped->getResultCacheId();
    }

    public function getSQL()
    {
        return $this->wrapped->getSQL();
    }

    public function getAST()
    {
        return $this->wrapped->getAST();
    }

    public function setQueryCacheDriver($queryCache)
    {
        return $this->wrapped->setQueryCacheDriver($queryCache);
    }

    public function useQueryCache($bool)
    {
        return $this->wrapped->useQueryCache($bool);
    }

    public function getQueryCacheDriver()
    {
        return $this->wrapped->getQueryCacheDriver();
    }

    public function setQueryCacheLifetime($timeToLive)
    {
        return $this->wrapped->setQueryCacheLifetime($timeToLive);
    }

    public function getQueryCacheLifetime()
    {
        return $this->wrapped->getQueryCacheLifetime();
    }

    public function expireQueryCache($expire = true)
    {
        return $this->wrapped->expireQueryCache($expire);
    }

    public function getExpireQueryCache()
    {
        return $this->wrapped->getExpireQueryCache();
    }

    public function free()
    {
        return $this->wrapped->free();
    }

    public function setDQL($dqlQuery)
    {
        return $this->wrapped->setDQL($dqlQuery);
    }

    public function getDQL()
    {
        return $this->wrapped->getDQL();
    }

    public function getState()
    {
        return $this->wrapped->getState();
    }

    public function contains($dql)
    {
        return $this->wrapped->contains($dql);
    }

    public function setFirstResult($firstResult)
    {
        return $this->wrapped->setFirstResult($firstResult);
    }

    public function getFirstResult()
    {
        return $this->wrapped->getFirstResult();
    }

    public function setMaxResults($maxResults)
    {
        return $this->wrapped->setMaxResults($maxResults);
    }

    public function getMaxResults()
    {
        return $this->wrapped->getMaxResults();
    }

    public function iterate($parameters = null, $hydrationMode = ORMQuery::HYDRATE_OBJECT)
    {
        return $this->wrapped->iterate($parameters, $hydrationMode);
    }

    public function setLockMode($lockMode)
    {
        return $this->wrapped->setLockMode($lockMode);
    }

    public function getLockMode()
    {
        return $this->wrapped->getLockMode();
    }

    protected function _doExecute()
    {}
}