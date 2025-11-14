<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function petugas(){
        return view('petugas.index');
    }

    public function siswa(){
        return view('siswa.index');
    }
}
