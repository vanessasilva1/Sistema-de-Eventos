<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    /*public function index() {
        $nome = "Vanessa";
        $idade = 20;
    
        $arr = [10,20,30,40,50];
        $nomes = ["Marta", "Allana", "Janaina"];
    
        return view('welcome', 
            [
                'nome' => $nome, 
                'idade' => $idade, 
                'profissao' => "DEV",
                'arr' => $arr,
                'nomes' => $nomes
            ]);
    }*/

    public function index() {
        
        $events = Event::all();
    
        return view('welcome', ['events' => $events]);
    }



    public function create() {
        return view('events.create');
    }
}
