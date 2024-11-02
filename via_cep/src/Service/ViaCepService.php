<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Psr\Log\LoggerInterface;

class ViaCepService
{
    private HttpClientInterface $client;
    private CacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, CacheInterface $cache, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function buscarEndereco(string $cep): array
    {
        $cacheKey = "endereco_$cep";
        
        // Retornando o item cacheado como endereco_cep, caso nao encontre, faz a requisicao para API do Via cep
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($cep) {
            $this->logger->info("Fazendo requisição à API ViaCEP para o CEP: $cep");

            // Tempo para expirar o cache, nesse caso 24h
            $item->expiresAfter(86400);

            $response = $this->client->request('GET', "https://viacep.com.br/ws/$cep/json/");

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Erro ao acessar a API do ViaCEP');
            }

            return $response->toArray();
        });
    }
}
