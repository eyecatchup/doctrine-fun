<?php
namespace Doctrine\Fun\Tests;

use Doctrine\Fun\EntityManagerDecorator;

class EntityManagerDecoratorTest extends \PHPUnit_Framework_TestCase
{
    private $em;
    private $decorator;
    private $query;

    public function setUp()
    {
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->decorator = new EntityManagerDecorator($this->em);
        $this->query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    public function testCreateQueryReturnsCustomQueryInstance()
    {
        $this->em
            ->expects($this->once())
            ->method('createQuery')
            ->with('DQL')
            ->will($this->returnValue($this->query));

        $query = $this->decorator->createQuery('DQL');

        $this->assertInstanceOf('Doctrine\Fun\Query', $query);
        $this->assertInstanceOf('Doctrine\ORM\AbstractQuery', $query->getWrappedQuery());
    }

    public function testCreateNamedQueryReturnsCustomQueryInstance()
    {
        $this->em
            ->expects($this->once())
            ->method('createNamedQuery')
            ->with('name')
            ->will($this->returnValue($this->query));

        $query = $this->decorator->createNamedQuery('name');

        $this->assertInstanceOf('Doctrine\Fun\Query', $query);
        $this->assertInstanceOf('Doctrine\ORM\AbstractQuery', $query->getWrappedQuery());
    }
}