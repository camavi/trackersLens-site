<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function summary(): JsonResponse
    {
        return response()->json([
            'kpis' => [
                ['label' => 'Total Boxes', 'value' => 24, 'delta' => 18, 'trend' => 'up'],
                ['label' => 'Cloud Assets', 'value' => 156, 'delta' => 23, 'trend' => 'up'],
                ['label' => 'API Requests', 'value' => 2400000, 'delta' => 31, 'trend' => 'up'],
                ['label' => 'Followers', 'value' => 1200, 'delta' => 12, 'trend' => 'up'],
            ],
            'box_segments' => [
                ['label' => 'Published', 'value' => 12],
                ['label' => 'Private', 'value' => 7],
                ['label' => 'Drafts', 'value' => 5],
            ],
        ]);
    }

    public function activity(): JsonResponse
    {
        return response()->json([
            'items' => [
                [
                    'type' => 'box_published',
                    'title' => 'Box published',
                    'detail' => 'Crypto Tracker',
                    'created_at' => now()->subMinutes(2)->toISOString(),
                ],
                [
                    'type' => 'asset_added',
                    'title' => 'New asset added',
                    'detail' => 'BTC Price WebSocket',
                    'created_at' => now()->subMinutes(15)->toISOString(),
                ],
                [
                    'type' => 'api_key_created',
                    'title' => 'API key generated',
                    'detail' => 'Production Key',
                    'created_at' => now()->subHour()->toISOString(),
                ],
            ],
        ]);
    }

    public function systemStatus(): JsonResponse
    {
        return response()->json([
            'services' => [
                ['name' => 'API Service', 'status' => 'operational'],
                ['name' => 'Cloud Storage', 'status' => 'operational'],
                ['name' => 'Database', 'status' => 'operational'],
                ['name' => 'WebSocket Gateway', 'status' => 'operational'],
                ['name' => 'Marketplace', 'status' => 'operational'],
            ],
        ]);
    }
}
