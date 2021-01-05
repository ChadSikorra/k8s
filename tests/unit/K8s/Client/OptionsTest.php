<?php

declare(strict_types=1);

namespace unit\K8s\Client;

use K8s\Client\Options;
use K8s\Core\Websocket\Contract\WebsocketClientInterface;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;

class OptionsTest extends TestCase
{
    /**
     * @var Options
     */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new Options(
            'https://foo.local/'
        );
    }

    public function testGetEndpoint(): void
    {
        $this->assertEquals('https://foo.local/', $this->subject->getEndpoint());
    }

    public function testGetNamespaceIsDefaultByDefault(): void
    {
        $this->assertEquals('default', $this->subject->getNamespace());
    }

    public function testGetAuthTypeIsTokenByDefault(): void
    {
        $this->assertEquals('token', $this->subject->getAuthType());
    }

    public function testGetSetHttpClient(): void
    {
        $client = \Mockery::spy(ClientInterface::class);
        $this->subject->setHttpClient($client);

        $this->assertEquals($client, $this->subject->getHttpClient());
    }

    public function testGetSetCache(): void
    {
        $cache = \Mockery::spy(CacheInterface::class);
        $this->subject->setCache($cache);

        $this->assertEquals($cache, $this->subject->getCache());
    }

    public function testGetSetWebSocket(): void
    {
        $websocket = \Mockery::spy(WebsocketClientInterface::class);
        $this->subject->setWebsocketClient($websocket);

        $this->assertEquals($websocket, $this->subject->getWebsocketClient());
    }

    public function testGetSetToken(): void
    {
        $this->subject->setToken('foo');

        $this->assertEquals('foo', $this->subject->getToken());
    }

    public function testGetSetUsername(): void
    {
        $this->subject->setUsername('foo');

        $this->assertEquals('foo', $this->subject->getUsername());
    }

    public function testGetSetPassword(): void
    {
        $this->subject->setPassword('foo');

        $this->assertEquals('foo', $this->subject->getPassword());
    }
}