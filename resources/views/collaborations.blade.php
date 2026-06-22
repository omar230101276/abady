@extends('layouts.app')

@section('title', 'Collaborations & Editorial Clients | Abady')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Title -->
    <div class="mb-12 border-b border-stone-200 pb-8">
        <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">Clients & Alliances</span>
        <h1 class="font-syne text-4xl font-extrabold text-stone-900 mt-2">COLLABORATIONS</h1>
    </div>

    @if($collaborations->isEmpty())
        <div class="bg-white border border-stone-200 rounded-xl p-16 text-center text-stone-400 text-sm shadow-sm">
            No brand collaborations listed yet. New portfolio campaigns will be published soon!
        </div>
    @else
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($collaborations as $collab)
                <div class="group bg-white border border-stone-200 rounded-2xl overflow-hidden shadow-sm flex flex-col hover:border-stone-300 hover:shadow-md transition-all duration-300">
                    <!-- Partner Image/Logo -->
                    <div class="aspect-video w-full bg-stone-50 border-b border-stone-150 overflow-hidden">
                        <img src="{{ asset('storage/' . $collab->image) }}" alt="{{ $collab->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-102">
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex flex-col items-center text-center">
                        <h3 class="font-syne font-bold text-lg text-stone-900 mb-1">{{ $collab->name }}</h3>
                        
                        @if($collab->description)
                            <p class="text-stone-500 text-sm font-light leading-relaxed max-w-xs border-t border-stone-100 pt-3 mt-1.5">
                                {{ $collab->description }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
