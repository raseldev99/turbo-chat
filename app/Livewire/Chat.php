<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chat extends Component
{
    public $receiver;
    public $messages;
    public $message = '';
    public function render()
    {
        $users = User::where('id','!=',auth()->id())->get();
        return view('livewire.chat',compact('users'));
    }

    public function addToMessage(User $user): void
    {
        $this->receiver = $user;
        $this->messages = Message::where(function ($query)use($user){
            $query->where('sender_id',auth()->id())->where('receiver_id',$user->id);
        })->orWhere(function ($query)use($user){
            $query->where('sender_id',$user->id)->where('receiver_id',auth()->id());
        })->get();
    }

    public function submit()
    {
        if (!empty($this->receiver) && !empty($this->message)){
           Message::create([
               'sender_id'=>auth()->id(),
               'receivar_id'
           ])
        }
    }
}
