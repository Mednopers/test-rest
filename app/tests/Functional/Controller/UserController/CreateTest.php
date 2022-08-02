<?php

namespace App\Tests\Functional\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateTest extends WebTestCase
{
    private KernelBrowser $client;

    private UrlGeneratorInterface $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();

        $this->generator = static::$kernel->getContainer()->get('router')->getGenerator();
    }

    /**
     * @dataProvider userCreationDataProvider
     */
    public function testSuccessfulUserCreation(array $payload, int $expectedResponseCode): void
    {
        $this->client->request(
            'POST',
            $this->generator->generate('api.users_user_create'),
            [],
            [],
            [
                'Content-Type' => 'application/json',
            ],
            \json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $this->assertEquals($expectedResponseCode, $this->client->getResponse()->getStatusCode());
        $this->assertNotNull($this->client->getResponse()->headers->get('Location'));
    }

    /**
     * @return array<string, array>
     */
    public function userCreationDataProvider(): array
    {
        return [
            'successful_user_creation' => [
                [
                    'name' => 'Bob Smith',
                    'email' => 'bob-smith@gmail.com',
                ],
                303
            ],
        ];
    }
}
