<?php

namespace App\Http\Controllers;

use App\ParentDetails;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function store(Request $request)
    {
        $requestParams = $request->all();

        //todo implement a proper rule for validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nic' => 'required|string|max:15',
        ]);

        $parent = ParentDetails::create($requestParams);

        return response()->json(['message' => 'Parent added successfully', 'parent' => $parent], 201);
    }
    public function getParentList()
    {
        $parents = ParentDetails::select('id', 'name', 'nic')->get();
        return response()->json($parents);
    }


}
