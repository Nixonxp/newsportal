<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceException;
use App\Http\Requests\SubscribeRequest;
use App\Http\Requests\UnsubscribeRequest;
use App\Services\Subscribe\SubscribeService;
use Illuminate\Http\Response;
use Log;

class SubscribeController extends Controller
{
    private SubscribeService $subscribeService;

    public function __construct(SubscribeService $subscribeService)
    {
        $this->subscribeService = $subscribeService;
        $this->middleware('throttle:100,1');
    }

    public function subscribe(SubscribeRequest $request)
    {
        try {
            $this->subscribeService->subscribeFromNewsId(\Auth::user()->id, $request->safe()->postId);
        } catch(ServiceException $e) {

            return response()->json([
                'errors' => [$e->getMessage()],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch(\Throwable $e) {

            Log::channel('database')->critical($e->getMessage());

            return response()->json([
                'errors' => __('errors.server_error')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'result' => 'ok',
            'message' => __('main.subscribe_success'),
        ]);
    }

    public function unsubscribe(UnsubscribeRequest $request)
    {
        try {
            $this->subscribeService->unsubscribe(\Auth::user()->id, $request->safe()->authorId);
        } catch(ServiceException $e) {

            return response()->json([
                'errors' => [$e->getMessage()],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch(\Throwable $e) {

            Log::channel('database')->critical($e->getMessage());

            return response()->json([
                'errors' => __('errors.server_error')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'result' => 'ok',
            'message' => __('main.unsubscribed'),
        ]);
    }
}
