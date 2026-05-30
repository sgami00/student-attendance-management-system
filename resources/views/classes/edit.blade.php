<x-layout>
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Class Information</h2>
        
        <form action="{{ route('classes.update', $class->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-gray-700">Class Name</label>
                <input type="text" name="name" value="{{ $class->name }}" class="w-full border rounded p-2" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1 text-gray-700">Class Code</label>
                <input type="text" name="code" value="{{ $class->code }}" class="w-full border rounded p-2" required>
            </div>
            
            <div class="flex justify-between items-center">
                <div class="space-x-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update Class</button>
                    <a href="{{ route('classes.show', $class->id) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
                </div>
        </form>

        <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this class? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium underline">Delete Class</button>
        </form>
            </div>
    </div>
</x-layout>