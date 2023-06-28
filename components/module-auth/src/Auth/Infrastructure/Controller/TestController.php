<?php

namespace App\Auth\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractBasePostController
{
    #[Route(path: '/test', name: 'test_controller')]
    public function __invoke(Request $request): JsonResponse
    {
        dd($this->data);
    }
}