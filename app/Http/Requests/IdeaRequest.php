<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Idea;
use Auth;

class IdeaRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ideaId = $this->route('id')?: $this->input('id');

        return $ideaId >0 ? Idea::where('id', $ideaId)
            ->where('user_id', Auth::id())->exists()
            : TRUE ;
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
