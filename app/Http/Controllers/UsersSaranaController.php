<?php

namespace App\Http\Controllers;

use App\Models\Sarana;
use Illuminate\Http\Request;

class UsersSaranaController extends Controller
{
    /**
     * Display a view-only listing of sarana for users.
     */
    public function index()
    {
        $saranas = Sarana::orderBy('id', 'desc')->get();
        return view('users.main.sarana.index', compact('saranas'));
    }
}
