<?php


namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class BookController extends AbstractController
{
    public const RESULTS_PER_PAGE = 10;
    public function index(Request $request): JsonResponse
    {
        $page = $request->query->get('page') ?? 0;

        $bookRepository = $this->getDoctrine()->getManager()->getRepository(BookRepository::class);
        $books = $bookRepository->getByPage($page);

        return new JsonResponse($books);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        return !empty($book) ? new JsonResponse($book) : new JsonResponse('No book found with ID = '. $id, 404);
    }

    public function store(Request $request): JsonResponse
    {
        $name = $request->request->get('name');
        $author = $request->request->get('author');
        $description = $request->request->get('description');

        $entityManager = $this->getDoctrine()->getManager();
        $book = new Book();
        $book->setName($name);
        $book->setAuthor($author);
        $book->setDescription($description);

        $entityManager->persist($book);
        $entityManager->flush();

        return new JsonResponse('OK');
    }
}