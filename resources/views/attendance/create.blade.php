{{-- File: resources/views/attendance/create.blade.php --}}
<x-layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1 bg-white p-6 rounded shadow border-t-4 border-indigo-600 h-fit sticky top-6">
            <h2 class="text-xl font-bold mb-2 text-gray-800">Scan QR Code Image</h2>
            <p class="text-xs text-gray-500 mb-4">Select, upload, or drag the student's QR code image here to automatically select their status from the list.</p>
            
            <div id="reader" class="w-full bg-gray-50 rounded overflow-hidden border-2 border-dashed border-gray-300"></div>
            
            <div id="scan-status" class="mt-4 p-3 rounded text-sm font-medium text-center hidden"></div>
        </div>

        <div class="md:col-span-2 bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-bold text-gray-800">Record Attendance for {{ $schoolClass->name }}</h2>
                <span class="text-xs bg-indigo-100 text-indigo-700 font-semibold px-2 py-1 rounded">Hybrid Input Active</span>
            </div>
            <p class="text-sm text-gray-500 mb-4">Class Code: <span class="font-mono bg-gray-100 px-1 rounded">{{ $schoolClass->code }}</span></p>
            
            <form id="attendance-form" action="{{ route('attendance.store', $schoolClass->id) }}" method="POST">
                @csrf
                <div class="mb-4 max-w-xs">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Session Date</label>
                    <input type="date" id="attendance_date" name="attendance_date" value="{{ date('Y-m-d') }}" class="w-full border rounded p-2" required>
                </div>

                <table class="w-full text-left mt-4 border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b text-gray-600 text-sm font-semibold">
                            <th class="p-3">Student ID</th>
                            <th class="p-3">Student Name</th>
                            <th class="p-3">Status Matrix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schoolClass->students as $student)
                            <tr class="border-b transition-colors duration-200" id="row-{{ $student->student_id_number }}">
                                <td class="p-3 text-gray-600 font-mono text-sm" id="id-col-{{ $student->student_id_number }}">{{ $student->student_id_number }}</td>
                                <td class="p-3 font-medium text-gray-800" id="name-col-{{ $student->student_id_number }}">{{ $student->name }}</td>
                                <td class="p-3 space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="statuses[{{ $student->id }}]" value="present" id="present-{{ $student->student_id_number }}" class="text-indigo-600"> 
                                        <span class="ml-1 text-sm text-gray-700">Present</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="statuses[{{ $student->id }}]" value="absent" id="absent-{{ $student->student_id_number }}" checked class="text-indigo-600"> 
                                        <span class="ml-1 text-sm text-gray-700">Absent</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="statuses[{{ $student->id }}]" value="late" id="late-{{ $student->student_id_number }}" class="text-indigo-600"> 
                                        <span class="ml-1 text-sm text-gray-700">Late</span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-2.5 rounded hover:bg-indigo-700 font-semibold w-full md:w-auto shadow transition-colors duration-150">
                    Save System Records
                </button>
            </form>
        </div>
    </div>

    <audio id="beep-sound" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-84.wav" preload="auto"></audio>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusDiv = document.getElementById('scan-status');
        const beep = document.getElementById('beep-sound'); 
        
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: 250, supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_FILE] },
            false
        );

        function onScanSuccess(decodedText, decodedResult) {
            const dataParts = decodedText.split('|');
            
            if (dataParts.length === 2) {
                const classId = dataParts[0].trim();
                const studentIdNumber = dataParts[1].trim(); 

                const presentRadio = document.getElementById(`present-${studentIdNumber}`);
                const studentRow = document.getElementById(`row-${studentIdNumber}`);
                const studentNameElement = document.getElementById(`name-col-${studentIdNumber}`);
                
                if (presentRadio && studentRow) {
                    const studentName = studentNameElement ? studentNameElement.textContent : "Student";

                    if(beep) {
                        beep.currentTime = 0; 
                        beep.play().catch(e => console.log("Audio pipeline block bypass"));
                    }

                    // Awtomatikong lilipat sa Present ang radio button sa listahan
                    presentRadio.checked = true;
                    
                    // Mag-fa-flash ng kulay berde ang row para alam ni teacher na nahanap ito
                    studentRow.style.backgroundColor = "#bbf7d0"; 
                    studentRow.style.transition = "all 0.3s ease";
                    
                    setTimeout(() => {
                        studentRow.style.backgroundColor = "#f0fdf4"; 
                    }, 800);

                    statusDiv.textContent = `✓ Scanned: ${studentName} (${studentIdNumber}) marked Present!`;
                    statusDiv.className = "mt-4 p-3 rounded text-sm font-medium text-center bg-green-100 text-green-800";
                    statusDiv.classList.remove('hidden');
                } else {
                    statusDiv.textContent = `⚠ ID Number (${studentIdNumber}) is not enrolled in this specific class section.`;
                    statusDiv.className = "mt-4 p-3 rounded text-sm font-medium text-center bg-red-100 text-red-800";
                    statusDiv.classList.remove('hidden');
                }
            } else {
                statusDiv.textContent = "✕ Error: Invalid QR Code data layout format.";
                statusDiv.className = "mt-4 p-3 rounded text-sm font-medium text-center bg-orange-100 text-orange-800";
                statusDiv.classList.remove('hidden');
            }
        }

        html5QrcodeScanner.render(onScanSuccess, (error) => {});
    });
</script>
</x-layout>