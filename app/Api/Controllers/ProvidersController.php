<?php
namespace Pulse\Api\Controllers;

use Pulse\Models\Provider;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Requests\Provider\GetAuthUrlRequest;
use Pulse\Services\Authorization\ResolverInterface;

class ProvidersController extends BaseController
{

    /**
     * Get the Authorization URL of the chosen provider
     * @param  GetAuthUrlRequest $request
     * @param  ResolverInterface $resolver
     * @return Response
     */
    public function getAuthUrl(GetAuthUrlRequest $request, ResolverInterface $resolver)
    {
        //Provider
        $provider = Provider::find($request->get('provider'));

        //Resolve Auth Provider
        $authProvider = $resolver->resolve(strtolower($provider->alias));

        //Get the Authorization URL
        $url = $authProvider->getAuthorizationUrl();

        //Return the Response
        return response()->json(['url' => $url]);
    }

}