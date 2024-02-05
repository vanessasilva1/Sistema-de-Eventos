<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;


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
        
        $search  = request('search');

        if($search) {

            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();

        } else {
            $events = Event::all();
        }

    
        return view('welcome', ['events' => $events, 'search' => $search]);
    }



    public function create() {
        return view('events.create');
    }

    public function store(Request $request) {

        $event = new Event;

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        //Image Upload

        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension; //Nome do path no banco

            $requestImage->move(public_path('img/events'), $imageName); //salva a imagem no servidor, como o nome definido na linha acima

            $event->image = $imageName; //salva no banco

        }

        $user = auth()->user(); //estou pegando o usuário logado
        $event->user_id = $user->id; //acessando a propriedade ID do usuário logado

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id) {

        $event = Event::findOrFail($id); //view resgatada que o cliente solicitou

        $user = auth()->user();
        $hasUserJoined = false;

        if($user) {

            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvent) {
                if($userEvent['id'] == $id) {
                    $hasUserJoined = true;
                }
            }
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray(); //Descobre o dono do evento: Vai fazer uma busca no banco, e o primeiro que ele encontrar com esse id idêntico, ele vai trazer em forma de array

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);

    }

    public function dashboard() {

        $user = auth()->user();

        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsasparticipant' => $eventsAsParticipant]);

    }

    public function destroy($id) {

        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');

    }

    public function edit($id) {

        $user = auth()->user();

        $event = Event::findOrFail($id);

        if($user->id != $event->user->id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]); //resgatamos os dados do bando e enviamos para essa view chamada edit
    }

    public function update(Request $request) {

        $event = Event::findOrFail($request->id);

        $data = $request->all();

        if($request->hasFile('image') && $request->file('image')->isValid()) {

            if ($event->image && file_exists(public_path('img/events/' . $event->image))) {

                unlink(public_path('img/events/' . $event->image));

            }

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension; //Nome do path no banco

            $requestImage->move(public_path('img/events'), $imageName); //salva a imagem no servidor, como o nome definido na linha acima

            $data['image'] = $imageName; //salva no banco

        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');

    }

    public function joinEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title);

    }

    public function leaveEvent ($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);

    }


}
