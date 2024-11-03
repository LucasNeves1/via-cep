<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EnderecoControllerTest extends WebTestCase
{
    public function testBuscarEnderecoComCepValido()
    {
        $client = static::createClient();
        
        // Defina o CEP de teste
        $cep = '01001000'; // Utilize um CEP válido para teste

        // Envia a requisição para a rota com o CEP
        $client->request('GET', '/endereco/' . $cep);

        // Verifique se a resposta está correta (status 200)
        $this->assertResponseIsSuccessful();

        // Verifique se o conteúdo retornado é JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Decodifica o JSON para verificar os dados
        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        // Verifique se o JSON tem as chaves esperadas (ajuste de acordo com o retorno da sua API)
        $this->assertArrayHasKey('cep', $data);
        $this->assertArrayHasKey('logradouro', $data);
        $this->assertArrayHasKey('bairro', $data);
        $this->assertArrayHasKey('localidade', $data);
        $this->assertArrayHasKey('uf', $data);
    }

    public function testBuscarEnderecoComCepInvalido()
    {
        $client = static::createClient();

        // Defina um CEP inválido
        $cep = '00000100';

        // Envia a requisição para a rota com o CEP inválido
        $client->request('GET', '/endereco/' . $cep);

        // Verifica se a resposta é bem-sucedida (deve ser 400 Bad Request)
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        // Decodifica o JSON retornado
        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        // Verifica se a resposta contém uma mensagem de erro
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('CEP nao encontrado', $data['error']);
    }
}
