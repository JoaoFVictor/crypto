<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Action\Cryptocurrency\CoinPriceFromDateTimeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cryptocurrency\CoinPriceFromDateTimeRequest;
use App\Http\Resources\Cryptocurrency\CoinPriceResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class CoinPriceFromDateTimeController extends Controller
{
    public function __construct(private readonly CoinPriceFromDateTimeAction $coinPriceFromDateTimeAction)
    {
    }

    public function __invoke(CoinPriceFromDateTimeRequest $request): JsonResource|JsonResponse
    {
        try {
            $coinPrice = $this->coinPriceFromDateTimeAction->handle($request->input('coin'), $request->input('date'));

            return CoinPriceResource::make($coinPrice);
        } catch (Exception $ex) {
            Log::critical('Controller'.self::class, ['exception' => $ex->getMessage()]);

            return Response::json(['message' => 'Server Error!'], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
