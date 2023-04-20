<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\UserCreateRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller
 */
#[Route('/')]
class IndexController extends AbstractController
{
    #[Route('', name: 'index', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json(['show' => 'must go on']);
    }
}
