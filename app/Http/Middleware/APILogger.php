<?php

namespace App\Http\Middleware;

use Closure;

class APILogger
{
    private $startTime;

    public function handle($request, Closure $next)
    {
        $this->startTime = microtime(true);
        return $next($request);
    }

    public function terminate($request, $response)
    {

        $folder_path = "logs/api";

        if (!file_exists(storage_path("$folder_path"))) {
            mkdir(storage_path("$folder_path"), 0777, true);
        }

        $endTime   = microtime(true);
        $filename  = 'Api-Datalogger_' . date('d-m-y H') . '.log';
        $dataToLog = 'Time                     : ' . gmdate("F j, Y, g:i:s a") . "\n";
        $dataToLog .= 'Duration                 : ' . number_format($endTime - LARAVEL_START, 3) . "\n";
        $dataToLog .= 'IP Address               : ' . $request->ip() . "\n";
        $dataToLog .= 'URL                      : ' . $request->fullUrl() . "\n";
        $dataToLog .= 'Method                   : ' . $request->method() . "\n";
        $dataToLog .= 'Accept-Header            : ' . $request->headers->get('Accept', '') . "\n";
        $dataToLog .= 'Authorization-Header     : ' . $request->headers->get('Authorization', '') . "\n";
        $dataToLog .= 'App-Version              : ' . $request->headers->get('App-Version', '') . "\n";
        $dataToLog .= 'Device-Type              : ' . $request->headers->get('Device-Type', '') . "\n";
        $dataToLog .= 'Device-Name              : ' . $request->headers->get('Device-Name', '') . "\n";
        $dataToLog .= 'Device-OS-Version        : ' . $request->headers->get('Device-OS-Version', '') . "\n";
        $dataToLog .= 'Device-UDID              : ' . $request->headers->get('Device-UDID', '') . "\n";
        $dataToLog .= 'Device-Push-Token        : ' . $request->headers->get('Device-Push-Token', '') . "\n";
        $dataToLog .= 'Input                    : ' . $request->getContent() . "\n";
        $dataToLog .= 'request                  : ' . json_encode($_REQUEST) . "\n";
        $dataToLog .= 'Output                   : ' . $response->getContent() . "\n";

        \File::append(storage_path("$folder_path" . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 100) . "\n\n");

    }

}
