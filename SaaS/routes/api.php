<?php

use App\Http\Controllers\Api\AiAgentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocataireController;
use App\Http\Controllers\Api\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::post('/login', [AuthController::class, 'login']);

// Debug route - remove in production
Route::get('/debug/users', function () {
    $users = \App\Models\User::select('id', 'name', 'email', 'account_type', 'is_connected')->get();
    return response()->json($users);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Push subscriptions
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy']);

    // AI Agent routes
    Route::post('/agent/message', [AiAgentController::class, 'message']);
    Route::get('/agent/history', [AiAgentController::class, 'history']);
    Route::delete('/agent/clear', [AiAgentController::class, 'clear']);
    Route::get('/agent/notifications', [AiAgentController::class, 'notifications']);
    Route::get('/agent/state', [AiAgentController::class, 'state']);

    // Locataire routes
    Route::get('/locataire/dashboard', [LocataireController::class, 'dashboard']);
    Route::post('/locataire/wallet/create', [LocataireController::class, 'createWallet']);
    Route::post('/locataire/wallet/recharge', [LocataireController::class, 'rechargeWallet']);
    Route::post('/locataire/wallet/pay-rent', [LocataireController::class, 'payRent']);
    Route::post('/locataire/wallet/pay-utility', [LocataireController::class, 'payUtility']);
    Route::post('/locataire/ticket/create', [LocataireController::class, 'createTicket']);
    Route::post('/locataire/wallet/transfer', [LocataireController::class, 'transferFunds']);
    Route::post('/locataire/contract-fee/pay', [LocataireController::class, 'payContractFee']);
});

// Immotok mobile API (public)
Route::get('/immotok/feed', [\App\Http\Controllers\ImmotokFeedController::class, 'getFeed']);
Route::post('/immotok/auth/register', [\App\Http\Controllers\ImmotokAuthController::class, 'register']);
Route::post('/immotok/auth/login', [\App\Http\Controllers\ImmotokAuthController::class, 'login']);
Route::get('/immotok/companies/{id}/profile', [\App\Http\Controllers\ImmotokFeedController::class, 'getCompanyProfile']);
Route::get('/immotok/categories', [\App\Http\Controllers\ImmotokFeedController::class, 'getCategories']);
Route::get('/immotok/media/{path}', function (\Illuminate\Http\Request $request, $path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    $fileSize = filesize($filePath);
    $mime = mime_content_type($filePath);
    $headers = [
        'Content-Type' => $mime,
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
    ];
    if ($request->headers->has('Range')) {
        $range = $request->header('Range');
        list($param, $range) = explode('=', $range, 2);
        if ($param == 'bytes') {
            list($rangeStart, $rangeEnd) = explode('-', $range, 2);
            $rangeStart = intval($rangeStart);
            $rangeEnd = $rangeEnd === '' ? $fileSize - 1 : intval($rangeEnd);
            $length = $rangeEnd - $rangeStart + 1;
            $fp = fopen($filePath, 'rb');
            fseek($fp, $rangeStart);
            $data = fread($fp, $length);
            fclose($fp);
            $headers['Content-Range'] = "bytes $rangeStart-$rangeEnd/$fileSize";
            $headers['Accept-Ranges'] = 'bytes';
            return response($data, 206, $headers);
        }
    }
    return response()->file($filePath, $headers);
})->where('path', '.*');

// Immotok mobile API (authenticated)
Route::middleware('immotok.token')->group(function () {
    Route::post('/immotok/auth/logout', [\App\Http\Controllers\ImmotokAuthController::class, 'logout']);
    Route::get('/immotok/auth/me', [\App\Http\Controllers\ImmotokAuthController::class, 'me']);
    Route::post('/immotok/illustrations/{id}/like', [\App\Http\Controllers\ImmotokFeedController::class, 'like']);
    Route::post('/immotok/illustrations/{id}/favorite', [\App\Http\Controllers\ImmotokFeedController::class, 'favorite']);
    Route::get('/immotok/illustrations/{id}/comments', [\App\Http\Controllers\ImmotokFeedController::class, 'getComments']);
    Route::post('/immotok/illustrations/{id}/comments', [\App\Http\Controllers\ImmotokFeedController::class, 'comment']);
    Route::post('/immotok/reserve-visit', [\App\Http\Controllers\ImmotokFeedController::class, 'reserveVisit']);
    Route::get('/immotok/chat/{company_id}', [\App\Http\Controllers\ImmotokChatController::class, 'getMessages']);
    Route::post('/immotok/chat/{company_id}', [\App\Http\Controllers\ImmotokChatController::class, 'sendMessage']);
    Route::post('/immotok/companies/{id}/subscribe', [\App\Http\Controllers\ImmotokFeedController::class, 'subscribe']);
    Route::get('/immotok/me/profile', [\App\Http\Controllers\ImmotokFeedController::class, 'getMyProfile']);
    Route::get('/immotok/notifications', [\App\Http\Controllers\ImmotokFeedController::class, 'getNotifications']);
    Route::get('/immotok/notifications/unread-count', [\App\Http\Controllers\ImmotokFeedController::class, 'getUnreadCount']);
    Route::post('/immotok/notifications/mark-read', [\App\Http\Controllers\ImmotokFeedController::class, 'markNotificationsRead']);
});
