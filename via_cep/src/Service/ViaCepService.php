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
        // Remover pontuação, pois a API só aceita CEP com números
        $cep = str_replace(['.', '-'], '', $cep);

        $cacheKey = "endereco_$cep";
        
        // Tenta obter o item do cache
        $cachedData = $this->cache->get($cacheKey, function (ItemInterface $item) use ($cep) {
            $this->logger->info("Fazendo requisição à API ViaCEP para o CEP: $cep");
            
            // Define o tempo de expiração do cache para 24 horas
            $item->expiresAfter(86400);
        
            $response = $this->client->request('GET', "https://viacep.com.br/ws/$cep/json/");

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Erro ao acessar a API do ViaCEP');
            }

            $result = $response->toArray();
        
            // Verifica se a API retornou um erro
            if (isset($result['erro']) && $result['erro'] === 'true') {
                // Armazena o erro no cache por um curto período (ex: 5 minutos)
                $this->logger->warning("CEP nao encontrado para o CEP: $cep");
                $item->expiresAfter(300); 
                throw new \Exception('CEP nao encontrado');
            }

            $this->logger->info("Dados obtidos com sucesso para o CEP: $cep");

            return $result;
        });

        // Verifica se os dados foram obtidos do cache
        if ($cachedData) {
            $this->logger->info("Dados retornados do cache para o CEP: $cep");
        }

        return $cachedData;
    }

}
