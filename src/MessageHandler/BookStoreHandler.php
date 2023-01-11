<?php

namespace App\MessageHandler;

use App\Message\BookStore;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\Persistence\ManagerRegistry;

class BookStoreHandler
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @throws Exception
     */
    public function __invoke(BookStore $book, ManagerRegistry $doctrine): void
    {
        sleep(5);

        $entityManager = $doctrine->getManager();
        $entityManager->getConnection()->beginTransaction();

        try {
            $entityManager->persist($book);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}