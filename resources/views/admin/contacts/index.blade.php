@extends('layouts.admin')

@section('title', 'Inbox / Messages')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-stone-200 pb-5">
        <div class="space-y-1">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 text-2xl">
                <i data-lucide="mail" class="w-6 h-6 text-stone-500"></i> Contact Submissions
            </h2>
            <p class="text-xs text-stone-500 font-light font-medium">Manage and review all contact queries received from the public website.</p>
        </div>
    </div>

    @if($contacts->isEmpty())
        <div class="bg-white border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm shadow-sm">
            No messages received yet. When users submit the public contact form, they will appear here!
        </div>
    @else
        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm">
            <div class="divide-y divide-stone-200">
                @foreach($contacts as $contact)
                    <div id="contact-card-{{ $contact->id }}" class="p-6 transition-all border-l-4 {{ $contact->is_read ? 'border-transparent bg-white' : 'border-amber-600 bg-amber-50/10' }} hover:bg-stone-50/50">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <!-- Info -->
                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="font-bold text-base text-stone-900">{{ $contact->name }}</h3>
                                    <a href="mailto:{{ $contact->email }}" class="text-xs text-amber-700 hover:underline flex items-center gap-1 font-medium">
                                        <i data-lucide="mail" class="w-3.5 h-3.5"></i> {{ $contact->email }}
                                    </a>
                                    @if($contact->phone)
                                        <span class="text-xs text-stone-500 flex items-center gap-1 font-medium">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-stone-400"></i> {{ $contact->phone }}
                                        </span>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <span id="badge-{{ $contact->id }}" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded-full tracking-wider {{ $contact->is_read ? 'bg-stone-100 text-stone-500' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $contact->is_read ? 'Read' : 'New' }}
                                    </span>
                                </div>
                                <span class="block text-xs text-stone-400 font-medium">
                                    Received {{ $contact->created_at->format('M d, Y \a\t h:i A') }} ({{ $contact->created_at->diffForHumans() }})
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- View Action -->
                                <button type="button" 
                                        onclick="openMessageModal({{ json_encode($contact) }})"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-stone-950 hover:bg-stone-850 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-colors cursor-pointer">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
                                </button>
                                
                                <!-- Toggle Read Form -->
                                <form id="form-toggle-{{ $contact->id }}" action="{{ $contact->is_read ? route('admin.contacts.unread', $contact->id) : route('admin.contacts.read', $contact->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 border border-stone-200 text-stone-700 hover:bg-stone-50 hover:text-stone-950 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors cursor-pointer bg-white">
                                        <i data-lucide="{{ $contact->is_read ? 'mail-open' : 'check' }}" class="w-3.5 h-3.5 text-stone-500"></i>
                                        <span>{{ $contact->is_read ? 'Mark Unread' : 'Mark Read' }}</span>
                                    </button>
                                </form>

                                <!-- Delete Action -->
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 border border-rose-200 text-rose-700 hover:bg-rose-50 hover:text-rose-900 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors cursor-pointer bg-white">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Message Content Preview -->
                        <div class="mt-4 p-4 bg-stone-50/50 hover:bg-stone-50 rounded-lg border border-stone-200 text-sm text-stone-750 line-clamp-3 leading-relaxed cursor-pointer transition-colors" onclick="openMessageModal({{ json_encode($contact) }})">
                            {{ $contact->message }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @endif
</div>

<!-- Message Details Modal -->
<div id="message-modal" class="fixed inset-0 z-50 bg-stone-900/40 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white border border-stone-200 rounded-xl max-w-2xl w-full shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-350 flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="p-6 border-b border-stone-200 flex items-center justify-between bg-stone-50/50">
            <h3 class="font-syne font-bold text-stone-900 text-lg flex items-center gap-2">
                <i data-lucide="mail-open" class="w-5 h-5 text-amber-600"></i> Message Details
            </h3>
            <button onclick="closeMessageModal()" class="p-1.5 text-stone-400 hover:text-stone-900 hover:bg-stone-100 rounded-full transition-colors cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6 space-y-6 overflow-y-auto flex-grow">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-stone-100 pb-5">
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-stone-400">Sender Name</span>
                    <span id="modal-sender-name" class="text-sm font-bold text-stone-900">-</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-stone-400">Received Date</span>
                    <span id="modal-received-date" class="text-sm font-medium text-stone-700">-</span>
                </div>
                <div class="mt-2">
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-stone-400">Email Address</span>
                    <a id="modal-sender-email" href="#" class="text-sm font-bold text-amber-700 hover:underline flex items-center gap-1 mt-0.5">
                        -
                    </a>
                </div>
                <div class="mt-2">
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-stone-400">Phone Number</span>
                    <span id="modal-sender-phone" class="text-sm font-medium text-stone-700">-</span>
                </div>
            </div>
            
            <div>
                <span class="block text-[10px] uppercase font-bold tracking-wider text-stone-400 mb-2">Message Content</span>
                <div id="modal-message-content" class="p-4 bg-stone-50 rounded-lg border border-stone-200 text-sm text-stone-850 whitespace-pre-line leading-relaxed">
                    -
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="p-6 border-t border-stone-200 flex justify-between bg-stone-50/50">
            <div class="flex gap-2">
                <form id="modal-unread-form" action="" method="POST" class="hidden">
                    @csrf
                </form>
                <button id="modal-unread-btn" onclick="markUnreadFromModal()" class="px-4 py-2 border border-stone-200 text-stone-700 hover:bg-stone-50 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center gap-1.5 cursor-pointer bg-white">
                    <i data-lucide="mail" class="w-4 h-4"></i> Mark Unread
                </button>
            </div>
            
            <button onclick="closeMessageModal()" class="px-5 py-2.5 bg-stone-900 hover:bg-stone-850 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-colors shadow-sm cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let currentContactId = null;

    function openMessageModal(contact) {
        currentContactId = contact.id;
        
        // Set modal content
        document.getElementById('modal-sender-name').textContent = contact.name;
        document.getElementById('modal-sender-email').textContent = contact.email;
        document.getElementById('modal-sender-email').href = `mailto:${contact.email}`;
        
        const date = new Date(contact.created_at);
        document.getElementById('modal-received-date').textContent = date.toLocaleString();
        document.getElementById('modal-sender-phone').textContent = contact.phone ? contact.phone : 'Not provided';
        document.getElementById('modal-message-content').textContent = contact.message;
        
        // Set unread form action URL
        const unreadForm = document.getElementById('modal-unread-form');
        unreadForm.action = `/admin/contacts/${contact.id}/unread`;
        
        // Show/hide unread button depending on current state
        const unreadBtn = document.getElementById('modal-unread-btn');
        if (contact.is_read) {
            unreadBtn.classList.remove('hidden');
        } else {
            unreadBtn.classList.add('hidden');
        }
        
        // Show modal container
        const modalContainer = document.getElementById('message-modal');
        modalContainer.classList.remove('hidden');
        setTimeout(() => {
            const dialog = modalContainer.querySelector('div');
            dialog.classList.remove('scale-95', 'opacity-0');
            dialog.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // If message is unread, mark it as read in background
        if (!contact.is_read) {
            markAsReadBackground(contact.id);
        }
    }

    function closeMessageModal() {
        const modalContainer = document.getElementById('message-modal');
        const dialog = modalContainer.querySelector('div');
        dialog.classList.remove('scale-100', 'opacity-100');
        dialog.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modalContainer.classList.add('hidden');
        }, 350);
    }

    function markUnreadFromModal() {
        if (currentContactId) {
            document.getElementById('modal-unread-form').submit();
        }
    }

    function markAsReadBackground(id) {
        fetch(`/admin/contacts/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI elements dynamically without page reload
                
                // 1. Update Card Styling
                const card = document.getElementById(`contact-card-${id}`);
                if (card) {
                    card.classList.remove('border-amber-600', 'bg-amber-50/10');
                    card.classList.add('border-transparent', 'bg-white');
                }
                
                // 2. Update Badge
                const badge = document.getElementById(`badge-${id}`);
                if (badge) {
                    badge.classList.remove('bg-amber-100', 'text-amber-800');
                    badge.classList.add('bg-stone-100', 'text-stone-500');
                    badge.textContent = 'Read';
                }
                
                // 3. Update Sidebar Unread Count Indicators
                const sidebarBadge = document.querySelector('a[href*="/contacts"] span.bg-amber-600');
                if (sidebarBadge) {
                    if (data.unread_count > 0) {
                        sidebarBadge.textContent = data.unread_count;
                    } else {
                        sidebarBadge.remove(); // Remove badge if zero unread
                    }
                }
                
                // 4. Update Toggle Read Form Action & Button
                const toggleForm = document.getElementById(`form-toggle-${id}`);
                if (toggleForm) {
                    toggleForm.action = `/admin/contacts/${id}/unread`;
                    const btn = toggleForm.querySelector('button');
                    if (btn) {
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.setAttribute('data-lucide', 'mail-open');
                        }
                        const text = btn.querySelector('span');
                        if (text) {
                            text.textContent = 'Mark Unread';
                        }
                    }
                }
                
                // 5. Update contact object dynamic state
                document.getElementById('modal-unread-btn').classList.remove('hidden');
                
                // Re-render lucide icons if changed
                if (window.lucide) {
                    lucide.createIcons();
                }
            }
        })
        .catch(err => console.error('Error marking as read:', err));
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMessageModal();
        }
    });
</script>
@endsection
