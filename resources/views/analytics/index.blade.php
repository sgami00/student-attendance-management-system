<x-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Attendance Analytics</h1>
        <p class="text-gray-600">Real-time breakdown of campus-wide student presence matrices.</p>
    </div>

    @php
        $total = $presentCount + $absentCount + $lateCount;
        $presentPercent = $total > 0 ? round(($presentCount / $total) * 100) : 0;
        $absentPercent = $total > 0 ? round(($absentCount / $total) * 100) : 0;
        $latePercent = $total > 0 ? round(($lateCount / $total) * 100) : 0;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Present Records</h3>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $presentCount }}</p>
            <p class="text-sm text-green-600 mt-2">↑ {{ $presentPercent }}% of total logs</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Absent Records</h3>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $absentCount }}</p>
            <p class="text-sm text-red-600 mt-2">↓ {{ $absentPercent }}% risk rate</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Late Records</h3>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $lateCount }}</p>
            <p class="text-sm text-yellow-600 mt-2">→ {{ $latePercent }}% punctuality variance</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Distribution Visualizer</h2>
        
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700">Present Rate</span>
                    <span class="text-gray-600 font-bold">{{ $presentPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full" style="width: {{ $presentPercent }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700">Late Rate</span>
                    <span class="text-gray-600 font-bold">{{ $latePercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-yellow-500 h-4 rounded-full" style="width: {{ $latePercent }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700">Absent Rate</span>
                    <span class="text-gray-600 font-bold">{{ $absentPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-red-500 h-4 rounded-full" style="width: {{ $absentPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</x-layout>