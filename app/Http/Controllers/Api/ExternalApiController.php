<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Exception;

class ExternalApiController extends Controller
{
    /**
     * Get current exchange rates from a free API
     * Uses exchangerate-api.com free tier (no API key required for basic usage)
     */
    public function getExchangeRates(): JsonResponse
    {
        try {
            // Using a free public API for exchange rates
            $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');
            
            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Exchange rates fetched successfully',
                    'base_currency' => $data['base'] ?? 'USD',
                    'date' => $data['date'] ?? now()->toDateString(),
                    'rates' => [
                        'EUR' => $data['rates']['EUR'] ?? null,
                        'GBP' => $data['rates']['GBP'] ?? null,
                        'JPY' => $data['rates']['JPY'] ?? null,
                        'INR' => $data['rates']['INR'] ?? null,
                        'CAD' => $data['rates']['CAD'] ?? null,
                        'AUD' => $data['rates']['AUD'] ?? null,
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch exchange rates',
            ], 503);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exchange rates',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
