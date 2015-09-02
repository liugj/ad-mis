<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\User;
use Auth;
class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $userId = $this->route('id') ?: $this->input('id');
        if ($userId > 0  &&Auth::admin()->get()->role !='admin') {
            return User::where('id', $userId)
                ->where('administrator_id', Auth::admin()->get()->id)->exists();
        }
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
