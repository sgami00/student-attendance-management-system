<x-layout>
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Create a New Class</h2>
        
        <form action="{{ route('classes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-gray-700">Class Name</label>
                <input type="text" name="name" placeholder="e.g., Intro to Web Development" class="w-full border rounded p-2" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1 text-gray-700">Class Code</label>
                <input type="text" name="code" placeholder="e.g., CS-101" class="w-full border rounded p-2" required>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Class</button>
                <a href="/dashboard" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>