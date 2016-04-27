<?php
namespace Pulse\Api\Requests\Account;

use Dingo\Api\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
        'name' => 'bail|required|string|max:255',
        'code' => 'bail|required|string',
        'state' => 'bail|required|string',
        'provider' => 'bail|required|exists:providers,alias',
        ];
    }
}
