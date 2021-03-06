<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;
class RechargeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $userId = $this->input('user_id');
        if (Auth ::admin()->get()->hasGrant('/admin/recharge/store') ){
            return User::where('id', $userId)->exists();
        }
        return false;
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
