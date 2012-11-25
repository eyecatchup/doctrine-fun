<?php
namespace Doctrine\Fun\Tests;

use Doctrine\Fun\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Version;
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
        $metadata->identifier = array('id');
        $this->repository = new EntityRepository($this->em, $metadata);
    }

    public function testFind_EntityFound()
    {
        $this->expectBasicLoad($this->entity, 123);
        $this->assertSomeOption($this->repository->find(123));
    }

    public function testFind_NoEntityFound()
    {
        $this->expectBasicLoad(null, 123);
        $this->assertNoneOption($this->repository->find(123));
    }

    public function testFindOne_EntityFound()
    {
        $this->expectPersisterLoad($this->entity, array('property' => 'value'));
        $this->assertSomeOption($this->repository->findOneBy(array('property' => 'value')));
    }

    public function testFindOne_NoEntityFound()
    {
        $this->expectPersisterLoad(null, array('property' => 'value'));
        $this->assertNoneOption($this->repository->findOneBy(array('property' => 'value')));
    }

    public function testFindOne_OrderBy_EntityFound()
    {
        $this->expectPersisterLoad($this->entity, array('property' => 'value'), array('id' => 'DESC'));
        $this->assertSomeOption($this->repository->findOneBy(array('property' => 'value'), array('id' => 'DESC')));
    }

    public function testFindOne_OrderBy_NoEntityFound()
    {
        $this->expectPersisterLoad(null, array('property' => 'value'), array('id' => 'DESC'));
        $this->assertNoneOption($this->repository->findOneBy(array('property' => 'value'), array('id' => 'DESC')));
    }

    public function testMagicFinder_EntityFound()
    {
        $this->expectPersisterLoad($this->entity, array('property' => 'value'));
        $this->assertSomeOption($this->repository->findOneByProperty('value'));
    }

    public function testMagicFinder_NoEntityFound()
    {
        $this->expectPersisterLoad(null, array('property' => 'value'));
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

    protected function expectPersisterLoad($entity, array $criteria, array $orderBy = null)
    {
        if ($orderBy !== null && Version::compare('2.4.0') === 1) {
            $this->markTestSkipped('EntityRepository::findOneBy(..., $orderBy) only supported in Doctrine >= 2.4.0');
        }

        $stub = $this->persister
            ->expects($this->once())
            ->method('load');

        $arguments = array($criteria, null, null, array(), 0, 1);
        if (Version::compare('2.4.0') === -1) {
            $arguments[] = $orderBy;
        }

        call_user_func_array(array($stub, 'with'), $arguments);
        $stub->will($this->returnValue($entity));
    }

    protected function expectBasicLoad($entity, $id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        if (Version::compare('2.2.0') === -1) {
            $this->em
                ->expects($this->once())
                ->method('find')
                ->with(get_class($this->entity), $id, $lockMode, $lockVersion)
                ->will($this->returnValue($entity));
        } else {
            $this->persister
                ->expects($this->once())
                ->method('load')
                ->with(array('id' => $id))
                ->will($this->returnValue($entity));
        }
    }
}
