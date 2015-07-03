<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use App\Plan;
use App\Idea;

class ReportRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $planId =  $this->input('plan_id', 0 );
        if ($planId) {
            return  Plan::where('id', $planId)
                ->where('user_id', Auth::id())->exists();
        }else{
            $ideaId =  $this->input('idea_id', 0 );
            if ($ideaId) {
                return  Idea::where('id', $ideaId)
                    ->where('user_id', Auth::id())->exists();
            }
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
