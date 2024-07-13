<?php

namespace App\Livewire;

use App\Events\MessageSendEvent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public $receiver;
    public $messages;
    public $sender_id;
    public $message = '';
    public function render()
    {
        $this->sender_id = auth()->id();
        $users = User::where('id','!=',auth()->id())->select('users.*')
            ->leftJoin(DB::raw('(SELECT receiver_id, MAX(created_at) AS latest_receive FROM messages GROUP BY receiver_id) AS received'), 'users.id', '=', 'received.receiver_id')
            ->leftJoin(DB::raw('(SELECT sender_id, MAX(created_at) AS latest_send FROM messages GROUP BY sender_id) AS sent'), 'users.id', '=', 'sent.sender_id')
            ->orderByRaw('COALESCE(sent.latest_send, received.latest_receive) DESC')
            ->get();
        return view('livewire.chat',compact('users'));
    }

    public function addToMessage(User $user): void
    {
        $this->receiver = $user;
        $this->fetchMessage($user);
    }

    public function submit(): void
    {
        if (!empty($this->receiver) && !empty($this->message)){
          $message = Message::create([
               'sender_id'=>auth()->id(),
               'receiver_id' => $this->receiver?->id,
               'message' => $this->message
           ]);
            $this->fetchMessage($this->receiver);
            $this->message = '';

            broadcast(new MessageSendEvent($message))->toOthers();
        }
    }

    public function fetchMessage($user): void
    {
        $this->messages = Message::where(function ($query)use($user){
            $query->where('sender_id',auth()->id())->where('receiver_id',$user->id);
        })->orWhere(function ($query)use($user){
            $query->where('sender_id',$user->id)->where('receiver_id',auth()->id());
        })->with(['sender:id,name,email','receiver:id,name,email'])->get();
    }

    #[On('echo:private-message.{sender_id},MessageSendEvent')]
    public function listMessage($event)
    {
      dd($event);
    }

}
