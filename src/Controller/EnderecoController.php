<?php

namespace App\Controller;

use App\Service\ViaCepService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EnderecoController extends AbstractController
{
    private ViaCepService $viaCepService;

    public function __construct(ViaCepService $viaCepService)
    {
        $this->viaCepService = $viaCepService;
    }

    #[Route('/endereco/{cep}', name: 'buscar_endereco', methods: ['GET'])]
    public function buscarEndereco(string $cep, Profiler $profiler = null): JsonResponse
    {
        try {
            $endereco = $this->viaCepService->buscarEndereco($cep);
            return new JsonResponse($endereco);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
