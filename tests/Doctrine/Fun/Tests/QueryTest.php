<?php
namespace Doctrine\Fun\Tests;

use Doctrine\Fun\Query;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Cache\QueryCacheProfile;

class NullQuery extends AbstractQuery
{
    protected function _doExecute()
    {}

    public function getSQL()
    {}
}

class QueryTest extends \PHPUnit_Framework_TestCase
{
    private $wrapped;

    /**
     * @var Query
     */
    private $query;

    private $entity;

    public function setUp()
    {
        $this->wrapped = $this->getMockBuilder('Doctrine\Fun\Tests\NullQuery')
            ->setMethods(array_diff(get_class_methods('Doctrine\ORM\Query'), array('__clone')))
            ->disableOriginalConstructor()
            ->getMock();
        $this->query = new Query($this->wrapped);
        $this->entity = new \stdClass();
    }

    public function getMethodParameters()
    {
        $class = new \ReflectionClass('Doctrine\ORM\Query');

        $methods = array();
        foreach ($class->getMethods() as $method) {
            if ($method->isConstructor() || $method->isStatic() || !$method->isPublic() || $method->getName() === '__clone') {
                continue;
            }

            /** Special case EntityManager::createNativeQuery() */
            if ($method->getName() === 'setResultSetMapping') {
                $methods[] = array($method->getName(), array(new ResultSetMapping()));
                continue;
            }

            if ($method->getName() === 'setHydrationCacheProfile' || $method->getName() === 'setResultCacheProfile') {
                $methods[] = array($method->getName(), array(new QueryCacheProfile()));
                continue;
            }

            if ($method->getNumberOfRequiredParameters() === 0) {
                $methods[] = array($method->getName(), array());
            } elseif ($method->getNumberOfRequiredParameters() > 0) {
                $methods[] = array($method->getName(), array_fill(0, $method->getNumberOfRequiredParameters(), 'req') ?: array());
            }
            if ($method->getNumberOfParameters() != $method->getNumberOfRequiredParameters()) {
                $methods[] = array($method->getName(), array_fill(0, $method->getNumberOfParameters(), 'all') ?: array());
            }
        }

        return $methods;
    }

    /**
     * @dataProvider getMethodParameters
     */
    public function testProperDelegation($method, array $parameters)
    {
        $stub = $this->wrapped
            ->expects($this->once())
            ->method($method)
            ->will($this->returnValue('VALUE ' . $method));
        call_user_func_array(array($stub, 'with'), $parameters);

        $this->assertSame('VALUE ' . $method, call_user_func_array(array($this->query, $method), $parameters));
    }

    public function testGetOptionResultWithEntity()
    {
        $option = $this->query->getOptionResult('MODE');

        $this->wrapped
            ->expects($this->once())
            ->method('getOneOrNullResult')
            ->with('MODE')
            ->will($this->returnValue($this->entity));

        $this->assertSame($this->entity, $option->get());
    }

    public function testGetOptionResultWithNull()
    {
        $option = $this->query->getOptionResult('MODE');

        $this->wrapped
            ->expects($this->once())
            ->method('getOneOrNullResult')
            ->with('MODE')
            ->will($this->returnValue(null));

        $this->assertTrue($option->isEmpty());
    }
}