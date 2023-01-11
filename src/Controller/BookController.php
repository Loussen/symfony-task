<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Message\BookStore;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookController extends AbstractController
{
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $books = $doctrine
            ->getRepository(Book::class)
            ->findAll();

        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'name' => $book->getTitle(),
                'pages' => $book->getPages(),
                'release_date' => $book->getReleaseDate(),
                'author' => $book->getAuthorId()->getName(),
            ];
        }


        return $this->json($data, 200);
    }

    public function new(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator, MessageBusInterface $bus): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

//        $entityManager = $doctrine->getManager();

        $columnNames = (new \App\Utils\EntityUtils)->get_column_names_by_entity(Book::class,$doctrine);

        $flipColumnNames = array_flip($columnNames);

        if(count(array_intersect_key($flipColumnNames,$data)) == count($data)){
            $author = $doctrine
                ->getRepository(Author::class)
                ->find($data['author_id']);

            if(!$author) {
                return $this->json('Author not found',404);
            }

            $book = new Book();

            $book->setTitle($data['title']);
            $book->setAuthorId($author);
            $book->setPages($data['pages']);
            $book->setReleaseDate(\DateTime::createFromFormat('d-m-Y H:i', $data['release_date']));

            $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();

            $violations = $validator->validate($book);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    echo $violation->getMessage() . '<br>';
                }

                return $this->json('Validation false',403);
            }

            $bus->dispatch(new BookStore($book));

//            $entityManager->persist($book);
//            $entityManager->flush();

            return $this->json('Created new book successfully',201);
        }

        return $this->json('Something is wrong!', 403);
    }
}
