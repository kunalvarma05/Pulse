<?php
namespace Pulse\Api\Controllers;

use Pulse\Models\Provider;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Api\Requests\Provider\GetAuthUrlRequest;

class ProvidersController extends BaseController
{

    /**
     * Get the Authorization URL of the chosen provider
     * @param  GetAuthUrlRequest $request
     * @param  ResolverInterface $resolver
     * @return Response
     */
    public function getAuthUrl(GetAuthUrlRequest $request)
    {
        //Provider
        $provider = Provider::find($request->get('provider'));

        //Resolve Authorization Service
        $authorization = AuthFactory::create(strtolower($provider->alias));

        //Get the Authorization URL
        $url = $authorization->getAuthorizationUrl();

        //Return the Response
        return response()->json(['url' => $url]);
    }

}