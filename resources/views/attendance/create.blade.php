{{-- File: resources/views/attendance/create.blade.php --}}
<x-layout>

<style>
    body { background: #eef2ff; }
    .mesh-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; background: #eef2ff; }
    .mesh-bg .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; animation: floatBlob linear infinite; }
    .blob-1 { width: 600px; height: 600px; background: radial-gradient(circle, #a5b4fc, #6366f1); top: -150px; left: -100px; animation-duration: 18s; }
    .blob-2 { width: 500px; height: 500px; background: radial-gradient(circle, #c4b5fd, #8b5cf6); top: 200px; right: -100px; animation-duration: 22s; animation-delay: -6s; }
    .blob-3 { width: 400px; height: 400px; background: radial-gradient(circle, #bae6fd, #38bdf8); bottom: 0px; left: 30%; animation-duration: 26s; animation-delay: -12s; }
    .blob-4 { width: 350px; height: 350px; background: radial-gradient(circle, #fbcfe8, #f472b6); bottom: 100px; right: 20%; animation-duration: 20s; animation-delay: -4s; }
    @keyframes floatBlob {
        0%   { transform: translate(0px, 0px) scale(1); }
        25%  { transform: translate(40px, -30px) scale(1.05); }
        50%  { transform: translate(-20px, 50px) scale(0.95); }
        75%  { transform: translate(-40px, -20px) scale(1.03); }
        100% { transform: translate(0px, 0px) scale(1); }
    }

    /* ── Modal overlay ── */
    #scan-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(30, 27, 75, 0.55);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
    }
    #scan-modal.active { display: flex; }

    #scan-modal-box {
        background: #fff;
        border-radius: 1.25rem;
        padding: 2rem 2rem 1.5rem;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(99,102,241,0.18);
        animation: popIn 0.22s cubic-bezier(.34,1.56,.64,1) both;
        position: relative;
    }
    @keyframes popIn {
        from { transform: scale(0.88); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }

    .status-btn {
        flex: 1;
        padding: 0.65rem 0;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.95rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.15s;
        outline: none;
    }
    .status-btn:focus { box-shadow: 0 0 0 3px rgba(99,102,241,0.3); }

    .status-btn.present  { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
    .status-btn.late     { background: #fef3c7; color: #78350f; border-color: #fcd34d; }
    .status-btn.absent   { background: #fee2e2; color: #7f1d1d; border-color: #fca5a5; }

    .status-btn.present.selected  { background: #059669; color: #fff; border-color: #059669; }
    .status-btn.late.selected     { background: #d97706; color: #fff; border-color: #d97706; }
    .status-btn.absent.selected   { background: #dc2626; color: #fff; border-color: #dc2626; }

    .status-btn:hover:not(.selected).present  { background: #a7f3d0; }
    .status-btn:hover:not(.selected).late     { background: #fde68a; }
    .status-btn:hover:not(.selected).absent   { background: #fecaca; }
</style>

<div class="mesh-bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
</div>

{{-- ── SCAN MODAL ── --}}
<div id="scan-modal" role="dialog" aria-modal="true" aria-labelledby="modal-student-name">
    <div id="scan-modal-box">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4">
            <div id="modal-avatar"
                 class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-lg shrink-0">
                --
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">QR Scanned</p>
                <p id="modal-student-name" class="text-lg font-bold text-gray-800 leading-tight"></p>
                <p id="modal-student-id"  class="text-xs text-gray-500 font-mono"></p>
            </div>
        </div>

        {{-- Status picker --}}
        <p class="text-sm font-semibold text-gray-600 mb-2">Mark as:</p>
        <div class="flex gap-2 mb-5">
            <button type="button" class="status-btn present" data-status="present" onclick="selectModalStatus('present')">
                ✓ Present
            </button>
            <button type="button" class="status-btn late"    data-status="late"    onclick="selectModalStatus('late')">
                ⏱ Late
            </button>
            <button type="button" class="status-btn absent"  data-status="absent"  onclick="selectModalStatus('absent')">
                ✕ Absent
            </button>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2">
            <button type="button"
                    onclick="confirmModalStatus()"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                Confirm
            </button>
            <button type="button"
                    onclick="closeModal()"
                    class="px-4 text-gray-500 hover:text-gray-700 font-medium text-sm rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>
{{-- ── END MODAL ── --}}

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1 bg-white bg-opacity-80 backdrop-blur-md p-6 rounded-2xl shadow border-t-4 border-indigo-600 h-fit sticky top-6">
            <h2 class="text-xl font-bold mb-2 text-gray-800">Scan QR Code Image</h2>
            <p class="text-xs text-gray-500 mb-4">Select, upload, or drag the student's QR code image here. A popup will appear for you to confirm their attendance status.</p>
            
            <div id="reader" class="w-full bg-gray-50 rounded overflow-hidden border-2 border-dashed border-gray-300"></div>
            
            <div id="scan-status" class="mt-4 p-3 rounded text-sm font-medium text-center hidden"></div>
        </div>

        <div class="md:col-span-2 bg-white bg-opacity-80 backdrop-blur-md p-6 rounded-2xl shadow">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-bold text-gray-800">Record Attendance for {{ $schoolClass->name }}</h2>
                <span class="text-xs bg-indigo-100 text-indigo-700 font-semibold px-2 py-1 rounded-full">Hybrid Input Active</span>
            </div>
            <p class="text-sm text-gray-500 mb-4">Class Code: <span class="font-mono bg-gray-100 px-1 rounded">{{ $schoolClass->code }}</span></p>
            
            <form id="attendance-form" action="{{ route('attendance.store', $schoolClass->id) }}" method="POST">
                @csrf
                <div class="mb-4 max-w-xs">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Session Date</label>
                    <input type="date" id="attendance_date" name="attendance_date" value="{{ date('Y-m-d') }}" class="w-full border rounded-lg p-2" required>
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
                
                <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 font-semibold w-full md:w-auto shadow transition-colors duration-150">
                    Save System Records
                </button>
            </form>
        </div>
    </div>

    <audio id="beep-sound" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-84.wav" preload="auto"></audio>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusDiv  = document.getElementById('scan-status');
        const beep       = document.getElementById('beep-sound');
        const modal      = document.getElementById('scan-modal');

        // Track which student is currently being confirmed
        let pendingStudentIdNumber = null;
        let selectedStatus         = 'present'; // default selection in modal

        // ── Open modal after a successful scan ──────────────────────────────
        function openModal(studentName, studentIdNumber) {
            pendingStudentIdNumber = studentIdNumber;
            selectedStatus         = 'present'; // reset to Present each time

            // Populate modal content
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('modal-student-id').textContent   = studentIdNumber;

            // Avatar initials
            const initials = studentName
                .split(' ')
                .map(w => w[0])
                .slice(0, 2)
                .join('')
                .toUpperCase();
            document.getElementById('modal-avatar').textContent = initials;

            // Reset button states, pre-select Present
            document.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('selected'));
            document.querySelector('.status-btn.present').classList.add('selected');

            modal.classList.add('active');
            // Trap focus inside modal
            document.querySelector('.status-btn.present').focus();
        }

        // ── Status button selection ──────────────────────────────────────────
        window.selectModalStatus = function(status) {
            selectedStatus = status;
            document.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('selected'));
            document.querySelector(`.status-btn.${status}`).classList.add('selected');
        };

        // ── Confirm button ───────────────────────────────────────────────────
        window.confirmModalStatus = function() {
            if (!pendingStudentIdNumber) return;

            const radio      = document.getElementById(`${selectedStatus}-${pendingStudentIdNumber}`);
            const studentRow = document.getElementById(`row-${pendingStudentIdNumber}`);
            const studentName = document.getElementById(`modal-student-name`).textContent;

            if (radio) {
                radio.checked = true;

                // Row highlight color by status
                const rowColors = {
                    present: { flash: '#bbf7d0', settle: '#f0fdf4' },
                    late:    { flash: '#fde68a', settle: '#fffbeb' },
                    absent:  { flash: '#fecaca', settle: '#fff1f2' },
                };
                const c = rowColors[selectedStatus];
                studentRow.style.backgroundColor = c.flash;
                studentRow.style.transition = 'all 0.3s ease';
                setTimeout(() => { studentRow.style.backgroundColor = c.settle; }, 800);

                // Scroll the row into view on the right panel
                studentRow.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Status bar feedback
                const icons   = { present: '✓', late: '⏱', absent: '✕' };
                const classes = {
                    present: 'bg-green-100 text-green-800',
                    late:    'bg-yellow-100 text-yellow-800',
                    absent:  'bg-red-100 text-red-800',
                };
                statusDiv.textContent  = `${icons[selectedStatus]} ${studentName} (${pendingStudentIdNumber}) marked ${selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1)}`;
                statusDiv.className    = `mt-4 p-3 rounded text-sm font-medium text-center ${classes[selectedStatus]}`;
                statusDiv.classList.remove('hidden');
            }

            closeModal();
        };

        // ── Close / cancel ───────────────────────────────────────────────────
        window.closeModal = function() {
            modal.classList.remove('active');
            pendingStudentIdNumber = null;
        };

        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
        });

        // ── QR Scanner ───────────────────────────────────────────────────────
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: 250, supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_FILE] },
            false
        );

        function onScanSuccess(decodedText, decodedResult) {
            const dataParts = decodedText.split('|');

            if (dataParts.length === 2) {
                const classId         = dataParts[0].trim();
                const studentIdNumber = dataParts[1].trim();

                const presentRadio        = document.getElementById(`present-${studentIdNumber}`);
                const studentNameElement  = document.getElementById(`name-col-${studentIdNumber}`);

                if (presentRadio && studentNameElement) {
                    const studentName = studentNameElement.textContent.trim();

                    if (beep) {
                        beep.currentTime = 0;
                        beep.play().catch(e => console.log("Audio pipeline block bypass"));
                    }

                    // Open modal instead of auto-marking
                    openModal(studentName, studentIdNumber);

                } else {
                    statusDiv.textContent = `⚠ ID Number (${studentIdNumber}) is not enrolled in this specific class section.`;
                    statusDiv.className   = "mt-4 p-3 rounded text-sm font-medium text-center bg-red-100 text-red-800";
                    statusDiv.classList.remove('hidden');
                }
            } else {
                statusDiv.textContent = "✕ Error: Invalid QR Code data layout format.";
                statusDiv.className   = "mt-4 p-3 rounded text-sm font-medium text-center bg-orange-100 text-orange-800";
                statusDiv.classList.remove('hidden');
            }
        }

        html5QrcodeScanner.render(onScanSuccess, (error) => {});
    });
</script>
</x-layout>