<?php

declare(strict_types=1);

namespace unit\K8s\Client;

use K8s\Api\Service\ServiceFactory;
use K8s\Client\Factory;
use K8s\Client\Http\HttpClient;
use K8s\Client\Http\RequestFactory;
use K8s\Client\Http\UriBuilder;
use K8s\Client\Kind\KindManager;
use K8s\Client\Metadata\MetadataCache;
use K8s\Client\Options;
use K8s\Client\Serialization\Serializer;
use K8s\Client\Websocket\WebsocketClientFactory;
use K8s\Core\Contract\ApiInterface;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new Factory(new Options('https://foo.local'));
    }

    public function testItMakesTheServiceFactory(): void
    {
        $result = $this->subject->makeServiceFactory();

        $this->assertInstanceOf(ServiceFactory::class, $result);
    }

    public function testItMakesTheSerializer(): void
    {
        $result = $this->subject->makeSerializer();

        $this->assertInstanceOf(Serializer::class, $result);
    }

    public function testItMakesTheKindManager(): void
    {
        $result = $this->subject->makeKindManager();

        $this->assertInstanceOf(KindManager::class, $result);
    }

    public function testItMakesTheApi(): void
    {
        $result = $this->subject->makeApi();

        $this->assertInstanceOf(ApiInterface::class, $result);
    }

    public function testItMakesTheWebSocketClientFactory(): void
    {
        $result = $this->subject->makeWebsocketClientFactory();

        $this->assertInstanceOf(WebsocketClientFactory::class, $result);
    }

    public function testItMakesTheHttpClient(): void
    {
        $result = $this->subject->makeHttpClient();

        $this->assertInstanceOf(HttpClient::class, $result);
    }

    public function testItMakesTheRequestFactory(): void
    {
        $result = $this->subject->makeRequestFactory();

        $this->assertInstanceOf(RequestFactory::class, $result);
    }

    public function testItMakesTheUriBuilder(): void
    {
        $result = $this->subject->makeUriBuilder();

        $this->assertInstanceOf(UriBuilder::class, $result);
    }

    public function testItMakesTheMetadataCache(): void
    {
        $result = $this->subject->makeMetadataCache();

        $this->assertInstanceOf(MetadataCache::class, $result);
    }
}