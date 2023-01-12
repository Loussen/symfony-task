<?php

namespace App\Command;

use App\Entity\Book;
use App\Message\BookStore;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('app:store-book')]
class BookStoreCommand extends Command
{
    private MessageBusInterface $bus;
    private Book $book;
    public function __construct(MessageBusInterface $bus, Book $book = null)
    {
        parent::__construct($book);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new BookStore("dsada"));
        return 0;
    }
}