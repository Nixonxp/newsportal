<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogListRequest;
use App\Models\Log;

class LogController extends Controller
{
    public function index(LogListRequest $request)
    {
        $logs = Log::select('id', 'message', 'level_name', 'datetime')
            ->when($request->type, function ($query) use ($request) {
                return $query->where('level_name', $request->type);
            })
            ->orderByDesc('unix_time')
            ->paginate(50);
        return view('admin.logs.index', compact('logs'));
    }

    public function show(int $logId)
    {
        $log = Log::findOrFail($logId);
        return view('admin.logs.show', compact('log'));
    }

    public function destroy(int $logId)
    {
        Log::find($logId)->delete();
        session()->flash('success', __('admin.delete_success'));
        return redirect(route('admin.logs.index'));
    }
}
