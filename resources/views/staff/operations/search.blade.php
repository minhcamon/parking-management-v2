@extends('staff.staff-layout')
@section('title', 'Search - Staff')

@section('content')
    <div class="max-w-[800px] mx-auto">

        <div class="text-center mb-12 mt-8">
            <i class="ph-fill ph-magnifying-glass text-5xl text-accent mb-4"></i>
            <h2 class="text-4xl mb-2">Quick Search</h2>
            <p class="text-muted">Lookup vehicle passing info by Card ID or License Plate</p>
        </div>

        <x-card class="mb-8 !p-[10px]">
            <form action="{{ route('staff.operations.search') }}" method="GET" class="flex items-center">
                <input type="text" name="query" value="{{ $query }}" placeholder="Enter License Plate or scan Card..." class="flex-1 p-6 text-xl border-none bg-transparent text-main outline-none font-mono" autofocus>
                <button type="submit" class="btn btn-primary mr-[5px] btn-lg btn-primary">
                    Search
                </button>
            </form>
        </x-card>

        @if($query && $sessions->isEmpty() && $passes->isEmpty())
            <x-card class="text-center py-12">
                <i class="ph ph-warning-circle text-5xl text-orange-400 mb-4"></i>
                <h3 class="font-bold text-xl">No information found</h3>
                <p class="text-muted">We couldn't find any active session or monthly pass matching"{{ $query }}"</p>
            </x-card>
        @endif

        @foreach($sessions as $session)
            <x-card class="border-l-[5px] border-l-[#10b981] mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                    <span class="bg-[#10b981]/20 text-[#34d399] px-3 py-1.5 rounded-[20px] text-sm font-bold tracking-[0.5px] uppercase">
                        <i class="ph-fill ph-check-circle"></i> Active Session
                    </span>
                    </div>
                    <div class="text-right">
                        <div class="font-heading text-xl font-bold text-main">
                            {{ $session->ticket_type->vehicleType->name ?? 'Vehicle' }}
                        </div>
                        <div class="text-[#34d399] font-bold">INSIDE</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-header-border pt-6">
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">License Plate</div>
                        <div class="text-3xl font-black font-mono text-accent">{{ $session->license_plate }}</div>
                    </div>
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">Check-in Time</div>
                        <div class="text-xl font-semibold text-main">
                            {{ \Carbon\Carbon::parse($session->check_in_time)->format('H:i d/m/Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">Card RFID</div>
                        <div class="text-xl font-semibold text-main">{{ $session->card->rfid_code ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">Duration</div>
                        <div class="text-xl font-semibold text-main">
                            {{ \Carbon\Carbon::parse($session->check_in_time)->diffForHumans(null, true) }}
                        </div>
                    </div>
                </div>
            </x-card>
        @endforeach

        @foreach($passes as $pass)
            <x-card class="border-l-[5px] border-l-[#6366f1] mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                    <span class="bg-[#6366f1]/20 text-[#6366f1] px-3 py-1.5 rounded-[20px] text-sm font-bold tracking-[0.5px] uppercase">
                        <i class="ph-fill ph-identification-card"></i> Monthly Pass
                    </span>
                    </div>
                    @php
                        $isExpired = \Carbon\Carbon::parse($pass->end_date)->isPast();
                    @endphp
                    <div class="text-right">
                        <div class="font-heading text-xl font-bold text-main">
                            {{ $pass->ticket_type->name ?? 'Category' }}
                        </div>
                        <div class="{{ $isExpired ? 'text-red-500' : 'text-[#6366f1]' }} font-bold">
                            {{ $isExpired ? 'EXPIRED' : 'ACTIVE' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-header-border pt-6">
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">License Plate</div>
                        <div class="text-3xl font-black font-mono text-main">{{ $pass->license_plate }}</div>
                    </div>
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">Subscriber</div>
                        <div class="text-xl font-semibold text-main uppercase">{{ $pass->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">Card RFID</div>
                        <div class="text-xl font-semibold text-main">{{ $pass->card->rfid_code ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-muted text-sm mb-1.5 uppercase font-bold tracking-wider">Valid Until</div>
                        <div class="text-xl font-semibold {{ $isExpired ? 'text-red-500' : 'text-main' }}">
                            {{ \Carbon\Carbon::parse($pass->end_date)->format('d/m/Y') }}
                            @if(!$isExpired)
                                <span class="text-sm text-[#10b981] font-normal italic">
                                ({{ \Carbon\Carbon::now()->diffInDays($pass->end_date) }} days left)
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>
        @endforeach

    </div>
@endsection
