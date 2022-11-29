<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Enums\Cryptocurrency\EnumCoin;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cryptocurrency\ValidCryptocurrencyNameResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ValidCryptocurrencyNameController extends Controller
{
    public function __invoke(): JsonResource|JsonResponse
    {
        try {
            $validCrypyocurrencyName = EnumCoin::cases();

            return ValidCryptocurrencyNameResource::collection($validCrypyocurrencyName);
        } catch (Exception $ex) {
            Log::critical('Controller'.self::class, ['exception' => $ex->getMessage()]);

            return Response::json(['message' => 'Server Error!'], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
