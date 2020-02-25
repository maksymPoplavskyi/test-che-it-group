<?php

namespace App\Services;

use App\Models\Logging;

class LoggingService
{
    public function createAction($request)
    {
        $createLogging = new Logging();

        $createLogging->action = $request->action;
        // ...
        $createLogging->save();

        return;
    }

    public function updateAction($request)
    {
        Logging::whereId($request->id)
            ->update([
                'action' => $request->action
                // ...
            ]);
    }

    public function deleteAction($id)
    {
        Logging::whereId($id)
            ->delete();
    }
}
