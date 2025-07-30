<?php

namespace App\Tests\Repository;

use App\Entity\Relation;
use App\Entity\Question;
use App\Repository\RelationRepository;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RelationRepositoryTest extends KernelTestCase
{
    private $em;
    private $relationRepository;
    private $questionRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $this->relationRepository = $container->get(RelationRepository::class);
        $this->questionRepository = $container->get(QuestionRepository::class);

        // Nettoyage des tables (en respectant les FK)
        $conn = $this->em->getConnection();
        $conn->executeStatement('DELETE FROM relation');
        $conn->executeStatement('DELETE FROM question');
        $conn->executeStatement('ALTER TABLE relation AUTO_INCREMENT = 1');
        $conn->executeStatement('ALTER TABLE question AUTO_INCREMENT = 1');
    }

    public function testAddAndFindRelation(): void
    {
        // Crée une question source
        $qSource = new Question();
        $qSource->setName('q_source_' . uniqid())
            ->setContent('Source?')
            ->setIsRootQuestion(true)
            ->setIsQuickQuestion(false);
        $this->questionRepository->add($qSource, true);

        // Crée une question cible
        $qTarget = new Question();
        $qTarget->setName('q_target_' . uniqid())
            ->setContent('Target?')
            ->setIsRootQuestion(false)
            ->setIsQuickQuestion(false);
        $this->questionRepository->add($qTarget, true);

        // Crée la relation entre les deux questions
        $relation = new Relation();
        $relation->setSource($qSource)
            ->setTargetQuestion($qTarget)
            ->setAnswer('oui');
        $this->relationRepository->add($relation, true);

        $found = $this->relationRepository->findOneBy(['answer' => 'oui']);
        $this->assertInstanceOf(Relation::class, $found);
        $this->assertEquals('oui', $found->getAnswer());
        $this->assertEquals($qSource->getId(), $found->getSource()->getId());
        $this->assertEquals($qTarget->getId(), $found->getTargetQuestion()->getId());
    }

    public function testRemoveRelation(): void
    {
        // Crée les questions
        $qSource = new Question();
        $qSource->setName('q_source_remove_' . uniqid())
            ->setContent('Source Remove?')
            ->setIsRootQuestion(true)
            ->setIsQuickQuestion(false);
        $this->questionRepository->add($qSource, true);

        $qTarget = new Question();
        $qTarget->setName('q_target_remove_' . uniqid())
            ->setContent('Target Remove?')
            ->setIsRootQuestion(false)
            ->setIsQuickQuestion(false);
        $this->questionRepository->add($qTarget, true);

        // Crée la relation
        $relation = new Relation();
        $relation->setSource($qSource)
            ->setTargetQuestion($qTarget)
            ->setAnswer('non');
        $this->relationRepository->add($relation, true);

        $id = $relation->getId();
        $this->assertNotNull($id);

        // Supprime et vérifie
        $this->relationRepository->remove($relation, true);
        $found = $this->relationRepository->find($id);
        $this->assertNull($found);
    }
}
