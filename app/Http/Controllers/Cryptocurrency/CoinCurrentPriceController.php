<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Action\Cryptocurrency\CoinCurrentPriceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cryptocurrency\CoinCurrentPriceRequest;
use App\Http\Resources\Cryptocurrency\CoinCurrentPriceResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class CoinCurrentPriceController extends Controller
{
    public function __construct(private readonly CoinCurrentPriceAction $coinCurrentPriceAction)
    {
    }

    public function __invoke(CoinCurrentPriceRequest $request): JsonResource|JsonResponse
    {
        try {
            $coinCurrentPrice = $this->coinCurrentPriceAction->handle($request->input('coin'));

            return CoinCurrentPriceResource::make($coinCurrentPrice);
        } catch (Exception $ex) {
            Log::critical('Controller'.self::class, ['exception' => $ex->getMessage()]);

            return Response::json(['message' => 'Server Error!'], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
