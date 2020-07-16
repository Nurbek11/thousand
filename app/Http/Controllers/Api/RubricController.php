<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RubricController extends Controller
{


    public function create(Request $request)
    {

        $request->validate([
            'title' => 'required',
        ]);
        $parentRubric = Rubric::all()->where('title', '=', $request->parentRubric)->last();
        $dublicateRubric = Rubric::all()->where('title', '=', $request->title)->last();
        if ($dublicateRubric==null) {
            $rubric = new Rubric();
            if ($parentRubric != null) {
                $rubric->parent_id = $parentRubric->id;
            }
            $rubric->title = $request->title;
            $rubric->save();
            return response(['rubric' => $rubric]);
        }else{
            return response(['message' => 'Duplicate Rubric']);
        }
    }

}
