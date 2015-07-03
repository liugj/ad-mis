<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Plan;
use Auth;

class PlanRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $planId = $this->route('id') ?: $this->input('id');
        if ($planId > 0 ) {
            return Plan::where('id', $planId)
                ->where('user_id', Auth::id())->exists();
        }else {
            $planId = $this->input('plan_id');
            if ($planId > 0 ) {
                return Plan::where('id', $planId)
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
