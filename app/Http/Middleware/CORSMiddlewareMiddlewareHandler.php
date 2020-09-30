<?php
namespace App\Http\Middleware;

use App\Http\IO\DefaultIOContextFactory;
use Closure;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareHandler;
use Framework\Http\Server\Response\ResponseStatusEnum;

class CORSMiddlewareMiddlewareHandler extends AbstractMiddlewareHandler
{
    public function handle(Closure $next, $data = null)
    {
        $request = DefaultIOContextFactory::get()->getRequest();
        $response = $request->getBoundContext()->getResponse();

        $response->header('Access-Control-Allow-Headers', '*');
        $response->header('Access-Control-Allow-Methods','GET, POST, OPTIONS');
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Credentials', 'true');

        if ($request->method() == 'OPTIONS') {
            return $response->status(ResponseStatusEnum::HTTP_OK_CODE)->send();
        }

        return $next();
    }
}