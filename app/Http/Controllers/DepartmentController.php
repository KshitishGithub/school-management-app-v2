<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(){
        return view('department.list-department');
    }

    public function add(){
        return view('department.add-department');
    }
    public function edit(){
        return view('department.edit-department');
    }
}
