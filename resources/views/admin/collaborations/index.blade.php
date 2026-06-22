@extends('layouts.admin')

@section('title', 'Manage Collaborations')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Collaborations Grid -->
    <div class="lg:col-span-2 space-y-6">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-stone-500"></i> Brands & Collaborations
        </h2>

        @if($collaborations->isEmpty())
            <div class="bg-white border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm shadow-sm">
                No collaborations added yet. Create one on the right!
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($collaborations as $collab)
                    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-stone-300 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full border border-stone-200 overflow-hidden bg-stone-50 flex-shrink-0">
                                    <img src="{{ $collab->thumbnail_url }}" alt="{{ $collab->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-stone-900">{{ $collab->name }}</h3>
                                </div>
                            </div>
                            @if($collab->description)
                                <p class="text-xs text-stone-600 mt-4 leading-relaxed line-clamp-3">
                                    {{ $collab->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="px-6 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50">
                            <a href="{{ route('admin.collaborations.edit', $collab->id) }}" class="flex items-center gap-1.5 text-xs font-bold text-stone-700 hover:text-stone-950 uppercase tracking-wider">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Details
                            </a>
                            
                            <form action="{{ route('admin.collaborations.destroy', $collab->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this collaboration?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-1.5 text-xs font-bold text-rose-700 hover:text-rose-900 uppercase tracking-wider">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $collaborations->links() }}
            </div>
        @endif
    </div>

    <!-- Create Card -->
    <div id="add-collab" class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm h-fit">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
            <i data-lucide="user-plus" class="w-5 h-5 text-emerald-600"></i> Add Collaborator
        </h2>

        <form action="{{ route('admin.collaborations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Collaborator Name / Brand</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="e.g. Vogue Arabia">
                @error('name')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Short Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                    placeholder="Describe the campaign or collaboration..."></textarea>
                @error('description')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Photo / Logo</label>
                <div class="border-2 border-dashed border-stone-200 rounded-lg p-4 bg-stone-50 hover:bg-stone-100 transition-colors cursor-pointer relative text-center">
                    <input type="file" id="image" name="image" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="updateFileName(this)">
                    <div class="space-y-2">
                        <i data-lucide="upload-cloud" class="w-8 h-8 text-stone-400 mx-auto"></i>
                        <p class="text-xs font-medium text-stone-600" id="file-name-placeholder">Click or drag logo/photo here</p>
                        <p class="text-[10px] text-stone-400">PNG, JPG or WEBP (Max. 10MB)</p>
                    </div>
                </div>
                @error('image')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Collaborator
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('file-name-placeholder');
        if (input.files && input.files[0]) {
            placeholder.textContent = input.files[0].name;
            placeholder.classList.add('text-amber-700');
        } else {
            placeholder.textContent = 'Click or drag logo/photo here';
            placeholder.classList.remove('text-amber-700');
        }
    }
</script>
@endsection
