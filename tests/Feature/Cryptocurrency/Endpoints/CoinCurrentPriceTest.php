<?php

namespace Tests\Feature\Cryptocurrency\Endpoints;

use App\Enums\Cryptocurrency\EnumCoin;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CoinCurrentPriceTest extends TestCase
{
    private const ROUTE = 'cryptocurrency.price.current';

    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    // public function test_the_application_returns_a_successful_response()
    // {
    //     $response = $this->getJson(route(self::ROUTE));

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'coin',
    //         ])->assertJson([
    //             'errors' => [
    //                 'coin' => ['The coin field is required.'],
    //             ]
    //         ], true);
    // }

    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    // public function test_the_application_returns_a_successful_response()
    // {
    //     $response = $this->getJson(route(self::ROUTE, ['coin' => 1]));

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'coin',
    //         ])->assertJson([
    //             'errors' => [
    //                 'coin' => ['The selected coin is invalid.'],
    //             ]
    //         ], true);
    // }

    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    // public function test_the_application_returns_a_successful_response()
    // {
    //     $mock = new MockHandler([
    //         new Psr7Response(200, [], 'a')
    //     ]);
    //     $handlerStack = HandlerStack::create($mock);
    //     $client = new Client(['handler' => $handlerStack]);
    //     $this->instance(Client::class, $client);

    //     $response = $this->getJson(route(self::ROUTE, ['coin' => EnumCoin::Bitcoin]))->dd();

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'coin',
    //         ])->assertJson([
    //             'errors' => [
    //                 'coin' => ['The selected coin is invalid.'],
    //             ]
    //         ], true);
    // }
}
