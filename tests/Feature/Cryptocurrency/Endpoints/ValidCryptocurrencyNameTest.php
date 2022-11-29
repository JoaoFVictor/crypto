<?php

namespace Tests\Feature\Cryptocurrency\Endpoints;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ValidCryptocurrencyNameTest extends TestCase
{
    private const ROUTE = 'cryptocurrency.names';

    /**
     * A test to validate valid response.
     *
     * @return void
     */
    public function test_successful_response()
    {
        $response = $this->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    [
                        'coin' => 'bitcoin',
                    ],
                    [
                        'coin' => 'ethereum',
                    ],
                    [
                        'coin' => 'dacxi',
                    ],
                    [
                        'coin' => 'cosmos',
                    ],
                ],
            ], true);
    }
}
