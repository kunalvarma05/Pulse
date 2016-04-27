<?php

namespace Pulse\Api\Requests\Provider;

use Pulse\Http\Requests\Request;

class GetAuthUrlRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'provider' => 'bail|required|exists:providers,alias'
        ];
    }
}
