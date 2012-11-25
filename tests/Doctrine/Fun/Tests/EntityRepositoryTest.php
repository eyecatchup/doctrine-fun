<?php
namespace Doctrine\Fun\Tests;

use Doctrine\Fun\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\DBAL\LockMode;
use stdClass;

class FunRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\ORM\UnitOfWork
     */
    private $uow;

    /**
     * @var \Doctrine\ORM\Persisters\BasicEntityPersister
     */
    private $persister;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var stdClass
     */
    private $entity;

    public function setUp()
    {
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->uow = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
            ->disableOriginalConstructor()
            ->getMock();
        $this->persister = $this->getMockBuilder('Doctrine\ORM\Persisters\BasicEntityPersister')
            ->disableOriginalConstructor()
            ->getMock();
        $this->em
            ->expects($this->any())
            ->method('getUnitOfWork')
            ->will($this->returnValue($this->uow));
        $this->uow
            ->expects($this->any())
            ->method('getEntityPersister')
            ->with('stdClass')
            ->will($this->returnValue($this->persister));

        $this->entity = new stdClass();
        $metadata = new ClassMetadata('stdClass');
        $metadata->fieldMappings = array('property' => 'property');
        $this->repository = new EntityRepository($this->em, $metadata);
    }

    public function testFind_EntityFound()
    {
        $this->em
            ->expects($this->once())
            ->method('find')
            ->with('stdClass', 123, LockMode::NONE, null)
            ->will($this->returnValue($this->entity));

        $this->assertSomeOption($this->repository->find(123));
    }

    public function testFind_NoEntityFound()
    {
        $this->em
            ->expects($this->once())
            ->method('find')
            ->with('stdClass', 123, LockMode::NONE, null);

        $this->assertNoneOption($this->repository->find(123));
    }

    public function testFindOne_EntityFound()
    {
        $this->persister
            ->expects($this->once())
            ->method('load')
            ->with(array('property' => 'value'), null, null, array(), 0, 1, null)
            ->will($this->returnValue($this->entity));

        $this->assertSomeOption($this->repository->findOneBy(array('property' => 'value')));
    }

    public function testFindOne_NoEntityFound()
    {
        $this->persister
            ->expects($this->once())
            ->method('load')
            ->with(array('property' => 'value'), null, null, array(), 0, 1, null);

        $this->assertNoneOption($this->repository->findOneBy(array('property' => 'value')));
    }

    public function testFindOne_OrderBy_EntityFound()
    {
        $this->persister
            ->expects($this->once())
            ->method('load')
            ->with(array('property' => 'value'), null, null, array(), 0, 1, array('id' => 'DESC'))
            ->will($this->returnValue($this->entity));

        $this->assertSomeOption($this->repository->findOneBy(array('property' => 'value'), array('id' => 'DESC')));
    }

    public function testFindOne_OrderBy_NoEntityFound()
    {
        $this->persister
            ->expects($this->once())
            ->method('load')
            ->with(array('property' => 'value'), null, null, array(), 0, 1, array('id' => 'DESC'));

        $this->assertNoneOption($this->repository->findOneBy(array('property' => 'value'), array('id' => 'DESC')));
    }

    public function testMagicFinder_EntityFound()
    {
        $this->persister
            ->expects($this->once())
            ->method('load')
            ->with(array('property' => 'value'), null, null, array(), 0, 1, null)
            ->will($this->returnValue($this->entity));

        $this->assertSomeOption($this->repository->findOneByProperty('value'));
    }

    public function testMagicFinder_NoEntityFound()
    {
        $this->persister
            ->expects($this->once())
            ->method('load')
            ->with(array('property' => 'value'), null, null, array(), 0, 1, null);

        $this->assertNoneOption($this->repository->findOneByProperty('value'));
    }

    protected function assertNoneOption($option)
    {
        $this->assertTrue($option->isEmpty());
        $this->assertFalse($option->isDefined());
    }

    protected function assertSomeOption($option)
    {
        $this->assertFalse($option->isEmpty());
        $this->assertTrue($option->isDefined());

        $this->assertSame($this->entity, $option->get());
    }
}
