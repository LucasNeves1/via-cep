<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EnderecoControllerTest extends WebTestCase
{
    public function testBuscarEnderecoComCepValido()
    {
        $client = static::createClient();
        
        // CEP Válido para teste
        $cep = '01001000'; 

        // Envia a requisição para a rota com o CEP
        $client->request('GET', '/endereco/' . $cep);

        // Verifique se a resposta está correta (status 200)
        $this->assertResponseIsSuccessful();

        // Verifique se o conteúdo retornado é JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Decodifica o JSON para verificar os dados
        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        // Verifique se o JSON tem as chaves esperadas
        $this->assertArrayHasKey('cep', $data);
        $this->assertArrayHasKey('logradouro', $data);
        $this->assertArrayHasKey('bairro', $data);
        $this->assertArrayHasKey('localidade', $data);
        $this->assertArrayHasKey('uf', $data);
    }

    public function testBuscarEnderecoComCepInvalido()
    {
        $client = static::createClient();

        // CEP inválido para teste
        $cep = '00000100';

        // Envia a requisição para a rota com o CEP inválido
        $client->request('GET', '/endereco/' . $cep);

        // Verifica se a resposta é bem-sucedida
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        // Decodifica o JSON retornado
        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        // Verifica se a resposta contém uma mensagem de erro
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('CEP nao encontrado', $data['error']);
    }
}
