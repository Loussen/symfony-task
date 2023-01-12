<?php

namespace App\MessageHandler;

use App\Entity\Author;
use App\Entity\Book;
use App\Message\BookStore;
use App\Repository\AuthorRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\Persistence\ManagerRegistry;

class BookStoreHandler implements MessageHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly AuthorRepository $authorRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(BookStore $bookStore): void
    {
        $book = new Book();

        $findAuthor = $this->authorRepository->find($bookStore->getData()['author_id']);

        $book->setTitle($bookStore->getData()['title']);
        $book->setAuthorId($findAuthor);
        $book->setPages($bookStore->getData()['pages']);
        $book->setReleaseDate(\DateTime::createFromFormat('d-m-Y H:i', $bookStore->getData()['release_date']));

//        dump($book);
//        sleep(5);


//        $entityManager->getConnection()->beginTransaction();

//        try {
            $this->em->persist($book);
            $this->em->flush();
//            $entityManager->getConnection()->commit();
//        }
//        catch (Exception $e) {
//            $entityManager->getConnection()->rollBack();
//            throw $e;
//        }
    }
}