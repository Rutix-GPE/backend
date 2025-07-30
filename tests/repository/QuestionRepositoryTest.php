<?php

namespace App\Tests\Repository;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionRepositoryTest extends KernelTestCase
{
    private $em;
    private $questionRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get('doctrine')->getManager();
        $this->questionRepository = static::getContainer()->get(QuestionRepository::class);

        // Nettoyage de la table (adapte si d'autres tables référencent question)
        $connection = $this->em->getConnection();
        $connection->executeStatement('DELETE FROM question');
        $connection->executeStatement('ALTER TABLE question AUTO_INCREMENT = 1');
    }

    public function testAddAndFindQuestion(): void
    {
        $question = new Question();
        $question->setName('q_test_' . uniqid());
        $question->setContent('Quelle est la capitale de la France ?');
        $question->setIsRootQuestion(true);
        $question->setIsQuickQuestion(false);

        $this->questionRepository->add($question, true);

        $found = $this->questionRepository->findOneBy(['name' => $question->getName()]);
        $this->assertInstanceOf(Question::class, $found);
        $this->assertEquals('Quelle est la capitale de la France ?', $found->getContent());
        $this->assertTrue($found->isRootQuestion());
        $this->assertFalse($found->isQuickQuestion());
    }

    public function testRemoveQuestion(): void
    {
        $question = new Question();
        $question->setName('q_remove_' . uniqid());
        $question->setContent('À supprimer');
        $question->setIsRootQuestion(false);
        $question->setIsQuickQuestion(false);
        $this->questionRepository->add($question, true);

        $id = $question->getId();
        $this->assertNotNull($id);

        // On le supprime
        $this->questionRepository->remove($question, true);
        $foundAgain = $this->questionRepository->find($id);
        $this->assertNull($foundAgain);
    }
}
