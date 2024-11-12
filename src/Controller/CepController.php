<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CepController extends AbstractController
{
    #[Route('/cep', name: 'app_cep')]
    public function index(): Response
    {
        return $this->render('cep/index.html.twig', [
            'controller_name' => 'CepController',
        ]);
    }

    #[Route('/cep/create', name: 'cep_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $cep = new Cep();
        $cep->setCep($data['cep']);
        $cep->setLogradouro($data['logradouro']);
        $cep->setBairro($data['bairro']);
        $cep->setLocalidade($data['localidade']);
        $cep->setUf($data['uf']);

        $entityManager->persist($cep);
        $entityManager->flush();

        return $this->json(['status' => 'CEP criado com sucesso!']);
    }
}
