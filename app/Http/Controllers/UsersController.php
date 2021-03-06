<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\PrivateMessage;
use App\User;
use App\Notifications\UserFollowed;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show($username)
    {
        //throw new \Exception("Simulando un error.");
        $user = $this->findByUsername($username);

        return view('users.show', [
            'user' => $user,
            'messages' => $user->messages()->paginate(10)
        ]);
    }

    /**
     * Primer parámetro hace referencia al username de la persona a seguir
     * Segundo parámetro obtenemos el id del usuario que tiene la sesión abierta
     */
    public function follow ($username, Request $request)
    {
        $user = $this->findByUsername($username);

        $me = $request->user();

        $me->follows()->attach($user);

        $user->notify(new UserFollowed($me));

        return redirect("/$username")->withSuccess('Usuario seguido!');
    }

    public function unfollow ($username, Request $request)
    {
        $user = $this->findByUsername($username);

        $me = $request->user();

        $me->follows()->detach($user);

        return redirect("/$username")->withSuccess('Usuario no seguido!');
    }    

    /**
     * Preguntar a un usuario que otros usuarios sigue
     */
    public function follows($username)
    {
        $user = $this->findByUsername($username);

        return view('users.follows', [
            'user' => $user,
            'follows' => $user->follows
        ]);
    }

    public function followers($username)
    {
        $user = $this->findByUsername($username);

        return view('users.follows', [
            'user' => $user,
            'follows' => $user->followers
        ]);
    }

    public function sendPrivateMessage($username, Request $request)
    {
        $user = $this->findByUsername($username);

        $me = $request->user();
        $message = $request->input('message');

        $conversation = Conversation::between($me, $user);

        /*$conversation = Conversation::create();
        $conversation->users()->attach($me);
        $conversation->users()->attach($user);*/

        $privateMessage = PrivateMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $me->id,
            'message' => $message,
        ]);

        return redirect('/conversations/'.$conversation->id);
    }

    public function showConversation(Conversation $conversation)
    {
        $conversation->load('users', 'privateMessages');
        
        return view('users.conversation', [
            'conversation' => $conversation,
            'user' => auth()->user(),
        ]);
    }

    private function findByUsername($username)
    {
        return User::where('username', $username)->firstOrFail();
    }

    public function notifications(Request $request)
    {
        return $request->user()->notifications;        
    }
}
