<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Scanner Viewport (Left/Middle) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col items-center">
            <h3 class="text-md font-bold mb-4 text-gray-700 dark:text-gray-300">كاميرا المسح / Scanner Camera</h3>
            
            <!-- Camera Select -->
            <div class="w-full max-w-md mb-4">
                <label for="camera-select" class="block text-xs font-semibold text-gray-500 mb-1">اختر الكاميرا / Select Camera</label>
                <select id="camera-select" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-2 text-sm text-gray-700 dark:text-gray-300">
                    <option value="">جاري تحميل الكاميرات... / Loading cameras...</option>
                </select>
            </div>

            <!-- Viewport Container -->
            <div class="relative w-full max-w-md aspect-video bg-gray-950 dark:bg-black rounded-lg overflow-hidden border-2 border-primary-500 flex items-center justify-center">
                <div id="reader" class="w-full h-full"></div>
                <!-- Scanning Line Animation -->
                <div class="absolute left-0 right-0 h-0.5 bg-red-500 shadow-md animate-bounce top-1/2 opacity-60"></div>
            </div>

            <div class="mt-4 flex gap-4">
                <button id="start-btn" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition-all shadow-sm">
                    بدء المسح / Start
                </button>
                <button id="stop-btn" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg text-sm transition-all">
                    إيقاف / Stop
                </button>
            </div>
        </div>

        <!-- Result Box (Right) -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-md font-bold mb-4 text-gray-700 dark:text-gray-300">حالة المسح / Scan Status</h3>
                
                <div id="status-container" class="flex flex-col items-center justify-center py-10 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg min-h-[300px]">
                    <div id="status-icon" class="text-gray-300 mb-4">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 11v.01M5.93 5.93l.71.71M18.07 18.07l.71.71M4 12h1m14 0h1M5.93 18.07l.71-.71M18.07 5.93l.71-.71M12 7a5 5 0 110 10 5 5 0 010-10z"></path></svg>
                    </div>
                    <div id="status-title" class="text-sm font-bold text-gray-400">في انتظار البطاقة...</div>
                    <div id="status-message" class="text-xs text-gray-500 text-center px-4 mt-2">يرجى تقريب رمز الـ QR من عدسة الكاميرا</div>
                </div>

                <!-- Attendee Details Card (Hidden by default) -->
                <div id="attendee-card" class="hidden bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 p-5 rounded-lg success-scan-animation">
                    <div class="flex items-center gap-3 mb-3 text-emerald-700 dark:text-emerald-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-sm">تم التحضير بنجاح / Attendance Verified</span>
                    </div>
                    <div class="space-y-2 text-xs text-gray-700 dark:text-gray-300">
                        <div><strong>المشارك / Attendee:</strong> <span id="res-name"></span></div>
                        <div><strong>المدرسة / School:</strong> <span id="res-school"></span></div>
                        <div><strong>وقت المسح / Time:</strong> <span id="res-time"></span></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 border-t border-gray-100 dark:border-gray-800 pt-4 text-center">
                <span class="text-xs text-primary-600 font-bold">منصة وجهة الرقمية 2026</span>
            </div>
        </div>
    </div>

    <!-- html5-qrcode scripts & Web Audio API -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html5QrCode = new Html5Qrcode("reader");
            const cameraSelect = document.getElementById('camera-select');
            const startBtn = document.getElementById('start-btn');
            const stopBtn = document.getElementById('stop-btn');
            const statusContainer = document.getElementById('status-container');
            const statusIcon = document.getElementById('status-icon');
            const statusTitle = document.getElementById('status-title');
            const statusMessage = document.getElementById('status-message');
            const attendeeCard = document.getElementById('attendee-card');
            const resName = document.getElementById('res-name');
            const resSchool = document.getElementById('res-school');
            const resTime = document.getElementById('res-time');

            let isScanning = false;
            let lastScanTime = 0;

            // Audio Context for Sound Effects
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

            function playSound(type) {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                if (type === 'success') {
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // High pitch A5
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.15);
                } else {
                    osc.frequency.setValueAtTime(150, audioCtx.currentTime); // Low buzz
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                }
            }

            // Get cameras list
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    cameraSelect.innerHTML = '';
                    devices.forEach(device => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.text = device.label || `Camera ${cameraSelect.length + 1}`;
                        cameraSelect.appendChild(option);
                    });
                } else {
                    cameraSelect.innerHTML = '<option value="">لم يتم العثور على كاميرات / No camera found</option>';
                }
            }).catch(err => {
                cameraSelect.innerHTML = '<option value="">فشل الوصول للكاميرا / Camera access blocked</option>';
            });

            startBtn.addEventListener('click', () => {
                const cameraId = cameraSelect.value;
                if (!cameraId || isScanning) return;

                html5QrCode.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (qrCodeMessage) => {
                        const now = Date.now();
                        // Prevent duplicate scan in 3 seconds window
                        if (now - lastScanTime < 3000) return;
                        lastScanTime = now;

                        handleQrCode(qrCodeMessage);
                    },
                    (errorMessage) => {
                        // Verbose logs ignored to avoid performance lag
                    }
                ).then(() => {
                    isScanning = true;
                    statusTitle.innerText = "جاري الكشف... / Scanning...";
                    statusMessage.innerText = "ضع الرمز في منتصف إطار الكاميرا";
                }).catch(err => {
                    console.error(err);
                });
            });

            stopBtn.addEventListener('click', () => {
                if (!isScanning) return;
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    statusTitle.innerText = "في انتظار البدء... / Standby";
                });
            });

            function handleQrCode(qrHash) {
                // Flash loading status
                statusContainer.classList.remove('hidden');
                attendeeCard.classList.add('hidden');
                statusTitle.innerText = "جاري التحقق... / Validating...";
                
                fetch('{{ route("api.attendance.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        qr_hash: qrHash,
                        device: 'ماسح كاميرا الويب / Web Camera'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        playSound('success');
                        statusContainer.classList.add('hidden');
                        
                        resName.innerText = data.attendee.name;
                        resSchool.innerText = data.attendee.school;
                        resTime.innerText = data.attendee.time;
                        
                        attendeeCard.classList.remove('hidden');

                        // Reset after 3 seconds
                        setTimeout(() => {
                            if (isScanning) {
                                attendeeCard.classList.add('hidden');
                                statusContainer.classList.remove('hidden');
                                statusTitle.innerText = "جاري الكشف... / Scanning...";
                            }
                        }, 3000);
                    } else {
                        playSound('error');
                        statusTitle.innerText = "فشل المسح / Scan Failed";
                        statusMessage.innerText = data.message;
                    }
                })
                .catch(err => {
                    playSound('error');
                    statusTitle.innerText = "خطأ اتصال / Connection Error";
                    statusMessage.innerText = "فشل التحقق بسبب مشكلة في الخادم.";
                });
            }
        });
    </script>
</x-filament-panels::page>
