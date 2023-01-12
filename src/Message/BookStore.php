<?php

namespace App\Message;

use App\Entity\Book;

class BookStore
{
    private array $data;

    public function __construct($data,$authorId)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

//    public function getBook(): Book
//    {
//        return $this->book;
//    }
}