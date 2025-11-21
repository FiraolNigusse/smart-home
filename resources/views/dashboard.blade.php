<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    
                    <!-- Devices Section -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Smart Devices</h3>
                        
                        @if($devices->count() > 0)
                            <div class="space-y-3">
                                @foreach ($devices as $device)
                                <form method="POST" action="{{ route('device.toggle', $device) }}" class="flex items-center justify-between p-4 border rounded-lg">
                                    @csrf
                                    <span class="text-gray-700">{{ $device->name }}</span>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                        {{ $device->state ? 'Turn Off' : 'Turn On' }} 
                                        <span class="ml-2">({{ $device->state ? 'ON' : 'OFF' }})</span>
                                    </button>
                                </form>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No devices found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add JavaScript here -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form[action*="toggle"]').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button');
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = 'Loading...';
                button.disabled = true;
                
                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: new FormData(this)
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        // Update button text without page reload
                        button.innerHTML = data.new_state ? 'Turn Off (ON)' : 'Turn On (OFF)';
                        button.disabled = false;
                    } else {
                        throw new Error('Request failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    button.innerHTML = originalText;
                    button.disabled = false;
                    alert('Error toggling device');
                }
            });
        });
    });
    </script>
</x-app-layout>