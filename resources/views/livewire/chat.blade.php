<div>
	<!-- component -->
	<div class="flex h-[calc(100vh-73px)] overflow-hidden">
		<!-- Sidebar -->
		<div class="w-1/4 bg-white border-r border-gray-300">
			<!-- Sidebar Header -->
			<header class="p-4 border-b border-gray-300 flex justify-between items-center bg-indigo-600 text-white">
				<h1 class="text-2xl font-semibold">Chat Web</h1>
				<div class="relative">
					<button id="menuButton" class="focus:outline-none">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-100" viewBox="0 0 20 20" fill="currentColor">
							<path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
							<path d="M2 10a2 2 0 012-2h12a2 2 0 012 2 2 2 0 01-2 2H4a2 2 0 01-2-2z" />
						</svg>
					</button>
				</div>
			</header>

			<!-- Contact List -->
			<div class="overflow-y-auto h-[calc(100vh-73px)] p-3 mb-9 pb-20">
				@foreach($users as $user)
					<div wire:click="addToMessage({{$user->id}})" class="flex items-center mb-4 cursor-pointer hover:bg-gray-100 p-2 rounded-md {{$user->id === $receiver?->id ? 'bg-gray-100' : ''}}">
						<div class="w-12 h-12 bg-gray-300 rounded-full mr-3">
							<img src="{{\Creativeorange\Gravatar\Facades\Gravatar::get($user->email)}}" alt="User Avatar" class="w-12 h-12 rounded-full">
						</div>
						<div class="flex-1">
							@php($lastMessage = $user->lastMessage())
							<h2 class="text-lg font-semibold">{{$user->name}}</h2>
							<p class="text-gray-600">{{!empty($lastMessage) ? $lastMessage->message : '...'}}</p>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		<!-- Main Chat Area -->
		<div class="flex-1">
			@if($receiver)
				<!-- Chat Header -->
				<header class="bg-white p-4 text-gray-700">
					<h1 class="text-2xl font-semibold">{{$receiver->name}}</h1>
				</header>

				<!-- Chat Messages -->
				<div class="h-[calc(100vh-73px)] overflow-y-auto p-4 pb-36">
					@forelse($messages as $message)
						@if($message->sender_id === auth()->id())
							<!-- Outgoing Message -->
							<div class="flex justify-end mb-4 cursor-pointer">
								<div class="flex max-w-96 bg-indigo-500 text-white rounded-lg p-3 gap-3">
									<p>{{$message->message}}</p>
								</div>
								<div class="w-9 h-9 rounded-full flex items-center justify-center ml-2">
									<img src="{{\Creativeorange\Gravatar\Facades\Gravatar::get(auth()->user()->email)}}" alt="My Avatar" class="w-8 h-8 rounded-full">
								</div>
							</div>
						@else
							<!-- Incoming Message -->
							<div class="flex mb-4 cursor-pointer">
								<div class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
									<img src="{{\Creativeorange\Gravatar\Facades\Gravatar::get($message->sender->email)}}" alt="User Avatar" class="w-8 h-8 rounded-full">
								</div>
								<div class="flex max-w-96 bg-white rounded-lg p-3 gap-3">
									<p class="text-gray-700">{{$message->message}}</p>
								</div>
							</div>
						@endif
					@empty
						<div class="">
							<div class="flex flex-col items-center pb-10">
								<img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{\Creativeorange\Gravatar\Facades\Gravatar::get($receiver->email)}}" alt="Bonnie image"/>
								<h5 class="mb-1 text-xl font-medium text-gray-900">{{$receiver->name}}</h5>
							</div>
						</div>
					@endforelse
				</div>

				<div class="relative">
					<!-- Chat Input -->
					<footer class="bg-white border-t border-gray-300 p-4 absolute bottom-[64px] w-full">
						<div class="flex items-center">
							<input wire:model="message" type="text" placeholder="Type a message..." class="w-full p-2 rounded-md border border-gray-400 focus:outline-none focus:border-blue-500">
							<button wire:click="submit()" class="bg-indigo-500 text-white px-4 py-2 rounded-md ml-2">Send</button>
						</div>
					</footer>
				</div>
			@else
				<div class="flex justify-center items-center h-full bg-white font-bold text-4xl">
					Inbox
				</div>
			@endif
		</div>
	</div>
</div>
