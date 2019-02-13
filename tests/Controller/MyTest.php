<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller;

use Tests\Sylius\ShopApiPlugin\Controller\Utils\ShopUserLoginTrait;

final class MyTest extends JsonApiTestCase
{
    use ShopUserLoginTrait;

    private static $acceptAndContentTypeHeader = ['CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json'];

    /**
     * @test
     */
    public function it_creates_one_cart_without_logged_user()
    {
        $this->loadFixturesFromFiles(['shop.yml']);

        $this->client->request('POST', '/shop-api/WEB_GB/carts', [], [], static::$acceptAndContentTypeHeader);
        $response = $this->client->getResponse();

        $orderRepository = $this->get('sylius.repository.order');
        $count = $orderRepository->count([]);

        $this->assertSame(1, $count, 'Only one cart should be created');
    }

    /**
     * @test
     */
    public function it_creates_one_cart_with_logged_user()
    {
        $this->loadFixturesFromFiles(['shop.yml', 'customer.yml']);

        $this->logInUser('oliver@queen.com', '123password');

        $this->client->request('POST', '/shop-api/WEB_GB/carts', [], [], static::$acceptAndContentTypeHeader);
        $response = $this->client->getResponse();

        $orderRepository = $this->get('sylius.repository.order');
        $count = $orderRepository->count([]);

        $this->assertSame(1, $count, 'Only one cart should be created');
    }
}
