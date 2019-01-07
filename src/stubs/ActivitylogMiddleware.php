<?php


namespace App\Http\Middleware;

use Closure;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
//use Monolog\Logger;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Cookie;


class ActivityLogMiddleware
{


    private $logger;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }
    protected function setVisitorId(){
        $id = Uuid::uuid();

        if(Cookie::get('visitor_id')!=null) return;

        Cookie::queue(Cookie::make('visitor_id', $id, 30));


    }

    public function handle(Request $request, Closure $next)
    {

        $this->setVisitorId();

        $uuid = Cookie::get('visitor_id')?
            decrypt(Cookie::get('visitor_id'),false):
            'processing...';


        $response = $next($request);

        $userId = auth()->id()? auth()->id() : auth()->guard('api')->id();

        $this->logger->info('Dump request', [
            'request'=>$request->method(),
            'request_ip' => $request->ip(),
            'user' => $userId ?:'anonymous-UUID-'.$uuid,
            'machine_user' => $request->server('USER'),
            'machine_type' => $request->server('HTTP_USER_AGENT'),
            'from' => $request->header('referer'),
            'page' => $request->url(),
            'query' => $request->query(),
        ]);

        return $response;

    }
}