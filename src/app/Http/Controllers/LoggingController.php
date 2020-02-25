<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoggingCreateRequest;
use App\Http\Requests\LoggingUpdateRequest;
use App\Models\Logging;
use App\Services\LoggingService;

class LoggingController extends Controller
{
    public function index()
    {
        $loggings = Logging::all();

        return view('welcome', compact('loggings'));
    }

    public function create(LoggingCreateRequest $request, LoggingService $service)
    {
        $service->createAction($request);
    }

    public function update(LoggingUpdateRequest $request, LoggingService $service)
    {
        $service->updateAction($request);
    }

    public function delete($id, LoggingService $service)
    {
        $service->deleteAction($id);
    }
}
