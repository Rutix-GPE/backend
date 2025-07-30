<?php

namespace App\Tests\Repository;

use App\Entity\Routine;
use App\Repository\RoutineRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoutineRepositoryTest extends KernelTestCase
{
    private $em;
    private $routineRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $this->routineRepository = $container->get(RoutineRepository::class);

        // Nettoyage pour éviter les problèmes d'unicité (name unique)
        $conn = $this->em->getConnection();
        $conn->executeStatement('DELETE FROM routine');
        $conn->executeStatement('ALTER TABLE routine AUTO_INCREMENT = 1');
    }

    public function testCreateAndFindRoutine(): void
    {
        $routine = new Routine();
        $routine->setName('RoutineTest_' . uniqid())
                ->setDescription('Description test')
                ->setDays([1, 2, 3])
                ->setTaskTime(new \DateTime('08:00'));

        $this->em->persist($routine);
        $this->em->flush();

        $found = $this->routineRepository->findOneBy(['name' => $routine->getName()]);
        $this->assertInstanceOf(Routine::class, $found);
        $this->assertEquals('Description test', $found->getDescription());
        $this->assertEquals([1, 2, 3], $found->getDays());
        $this->assertEquals('08:00', $found->getTaskTime()->format('H:i'));
    }

    public function testRemoveRoutine(): void
    {
        $routine = new Routine();
        $routine->setName('RoutineRemove_' . uniqid())
                ->setDescription('À supprimer')
                ->setDays([4, 5])
                ->setTaskTime(new \DateTime('09:00'));
        $this->em->persist($routine);
        $this->em->flush();

        $id = $routine->getId();
        $this->assertNotNull($id);

        $this->em->remove($routine);
        $this->em->flush();

        $found = $this->routineRepository->find($id);
        $this->assertNull($found);
    }
}
