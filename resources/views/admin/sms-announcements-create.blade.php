<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create SMS Announcement - SmartHarvest Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🌾</span>
                    <h1 class="text-xl font-bold text-green-700">SmartHarvest Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @if($balance['success'])
                    <div class="bg-green-100 border border-green-300 rounded-lg px-3 py-1">
                        <p class="text-sm text-green-700">
                            <span class="font-semibold">Credits:</span> {{ $balance['balance'] ?? 'N/A' }}
                        </p>
                    </div>
                    @endif
                    <a href="{{ route('admin.sms.index') }}" class="text-gray-600 hover:text-green-600 transition">
                        ← Back to Announcements
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Create SMS Announcement</h2>
            <p class="text-gray-600 mt-1">Send important messages to farmers via SMS</p>
        </div>

        <!-- Alert Container -->
        <div id="alert-container" class="mb-6"></div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="announcement-form" class="space-y-6">
                @csrf

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message *
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        rows="4" 
                        maxlength="160"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Enter your announcement message (max 160 characters)"
                        required
                    ></textarea>
                    <div class="flex justify-between mt-2">
                        <p class="text-sm text-gray-500">SMS messages are limited to 160 characters</p>
                        <p class="text-sm font-medium" id="char-count">0/160</p>
                    </div>
                </div>

                <!-- Recipient Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Recipients *
                    </label>
                    <div class="space-y-3">
                        <!-- All Farmers -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input 
                                type="radio" 
                                name="recipient_type" 
                                value="all" 
                                class="w-4 h-4 text-green-600 focus:ring-green-500"
                                checked
                            >
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">All Farmers</p>
                                <p class="text-xs text-gray-500">Send to all registered farmers</p>
                            </div>
                        </label>

                        <!-- By Municipality -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input 
                                type="radio" 
                                name="recipient_type" 
                                value="municipality" 
                                class="w-4 h-4 text-green-600 focus:ring-green-500"
                            >
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">By Municipality</p>
                                <p class="text-xs text-gray-500">Filter farmers by location</p>
                            </div>
                        </label>

                        <!-- Municipality Select (Hidden by default) -->
                        <div id="municipality-select" class="ml-7 hidden">
                            <select 
                                id="municipality" 
                                name="municipality" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">Select Municipality</option>
                                @foreach($municipalities as $municipality)
                                <option value="{{ $municipality }}">{{ $municipality }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selected Farmers -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input 
                                type="radio" 
                                name="recipient_type" 
                                value="selected" 
                                class="w-4 h-4 text-green-600 focus:ring-green-500"
                            >
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Selected Farmers</p>
                                <p class="text-xs text-gray-500">Choose specific farmers</p>
                            </div>
                        </label>

                        <!-- Farmer Select (Hidden by default) -->
                        <div id="farmer-select" class="ml-7 hidden">
                            <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto">
                                @foreach($farmers as $farmer)
                                <label class="flex items-center py-2 hover:bg-gray-50 px-2 rounded cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="recipients[]" 
                                        value="{{ $farmer->id }}"
                                        class="w-4 h-4 text-green-600 focus:ring-green-500 rounded"
                                    >
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $farmer->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $farmer->location }} - {{ substr($farmer->phone ?? $farmer->phone_number, -4) }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Recipients -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-blue-800">Recipients Preview</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Message will be sent to <span id="recipient-count" class="font-semibold">0</span> farmer(s)
                            </p>
                            <button 
                                type="button" 
                                id="preview-btn"
                                class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium"
                            >
                                Preview Recipients →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a 
                        href="{{ route('admin.sms.index') }}" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        id="send-btn"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-8 rounded-lg transition duration-200 flex items-center space-x-2"
                    >
                        <span id="send-text">Send Announcement</span>
                        <span id="send-loading" class="hidden">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Character counter
        const messageInput = document.getElementById('message');
        const charCount = document.getElementById('char-count');
        
        messageInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/160`;
            
            if (length > 160) {
                charCount.classList.add('text-red-600');
            } else {
                charCount.classList.remove('text-red-600');
            }
        });

        // Recipient type toggle
        const recipientRadios = document.querySelectorAll('input[name="recipient_type"]');
        const municipalitySelect = document.getElementById('municipality-select');
        const farmerSelect = document.getElementById('farmer-select');

        recipientRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                municipalitySelect.classList.add('hidden');
                farmerSelect.classList.add('hidden');

                if (this.value === 'municipality') {
                    municipalitySelect.classList.remove('hidden');
                } else if (this.value === 'selected') {
                    farmerSelect.classList.remove('hidden');
                }

                updateRecipientCount();
            });
        });

        // Update recipient count
        function updateRecipientCount() {
            const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
            const recipientCountEl = document.getElementById('recipient-count');

            if (recipientType === 'all') {
                recipientCountEl.textContent = '{{ $farmers->count() }}';
            } else if (recipientType === 'municipality') {
                // This will be updated via AJAX preview
                recipientCountEl.textContent = '...';
            } else if (recipientType === 'selected') {
                const checked = document.querySelectorAll('input[name="recipients[]"]:checked').length;
                recipientCountEl.textContent = checked;
            }
        }

        // Listen to checkbox changes for selected farmers
        document.querySelectorAll('input[name="recipients[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateRecipientCount);
        });

        // Preview recipients
        document.getElementById('preview-btn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const formData = new FormData(document.getElementById('announcement-form'));
            
            fetch('/admin/sms/preview', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('recipient-count').textContent = data.count;
                
                if (data.count === 0) {
                    showAlert('warning', 'No recipients match your selection.');
                } else {
                    showAlert('success', `Will send to ${data.count} recipient(s)`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Failed to preview recipients.');
            });
        });

        // Form submission
        document.getElementById('announcement-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            
            if (!message) {
                showAlert('error', 'Please enter a message.');
                return;
            }

            if (message.length > 160) {
                showAlert('error', 'Message exceeds 160 characters.');
                return;
            }

            const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
            
            if (recipientType === 'selected') {
                const checked = document.querySelectorAll('input[name="recipients[]"]:checked').length;
                if (checked === 0) {
                    showAlert('error', 'Please select at least one farmer.');
                    return;
                }
            }

            if (!confirm('Are you sure you want to send this announcement? This action cannot be undone.')) {
                return;
            }

            showLoading();

            const formData = new FormData(this);

            fetch('/admin/sms/send', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', `${data.message}\nSent: ${data.sent}, Failed: ${data.failed}`);
                    
                    setTimeout(() => {
                        window.location.href = '/admin/sms';
                    }, 2000);
                } else {
                    showAlert('error', data.message || 'Failed to send announcement.');
                    hideLoading();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred. Please try again.');
                hideLoading();
            });
        });

        // Alert functionality
        function showAlert(type, message) {
            const colors = {
                success: 'bg-green-100 border-green-400 text-green-700',
                error: 'bg-red-100 border-red-400 text-red-700',
                warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
            };

            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="border-l-4 p-4 ${colors[type]} rounded">
                    <p class="font-medium whitespace-pre-line">${message}</p>
                </div>
            `;

            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            if (type === 'success') {
                setTimeout(() => {
                    alertContainer.innerHTML = '';
                }, 5000);
            }
        }

        // Loading state
        function showLoading() {
            const btn = document.getElementById('send-btn');
            btn.disabled = true;
            document.getElementById('send-text').classList.add('hidden');
            document.getElementById('send-loading').classList.remove('hidden');
        }

        function hideLoading() {
            const btn = document.getElementById('send-btn');
            btn.disabled = false;
            document.getElementById('send-text').classList.remove('hidden');
            document.getElementById('send-loading').classList.add('hidden');
        }

        // Initialize count
        updateRecipientCount();
    </script>
</body>
</html>
