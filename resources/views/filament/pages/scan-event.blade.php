<x-filament-panels::page>
    <style>
        .scanner-hud-container {
            position: relative;
            width: 100%;
            max-width: 480px;
            aspect-ratio: 4 / 3;
            background-color: #030712;
            border-radius: 1.25rem;
            overflow: hidden;
            border: 2px solid #1f2937;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        
        .hud-corner {
            position: absolute;
            width: 24px;
            height: 24px;
            border: 4px solid #FF4900; /* Wejha Brand Orange Accent */
            z-index: 10;
            pointer-events: none;
        }
        .hud-tl { top: 16px; left: 16px; border-right: none; border-bottom: none; border-top-left-radius: 8px; }
        .hud-tr { top: 16px; right: 16px; border-left: none; border-bottom: none; border-top-right-radius: 8px; }
        .hud-bl { bottom: 16px; left: 16px; border-right: none; border-top: none; border-bottom-left-radius: 8px; }
        .hud-br { bottom: 16px; right: 16px; border-left: none; border-top: none; border-bottom-right-radius: 8px; }

        .laser-line {
            position: absolute;
            left: 16px;
            right: 16px;
            height: 3px;
            background: linear-gradient(to right, transparent, #FF4900, #ff8753, #FF4900, transparent);
            box-shadow: 0 0 10px #FF4900, 0 0 20px rgba(255, 73, 0, 0.5);
            z-index: 5;
            animation: laser-scan 2.5s infinite ease-in-out;
            pointer-events: none;
            display: none;
        }

        @keyframes laser-scan {
            0% { top: 12%; }
            50% { top: 88%; }
            100% { top: 12%; }
        }

        .radar-sweep {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid rgba(0, 31, 143, 0.15);
            background: radial-gradient(circle, rgba(0, 31, 143, 0.05) 0%, transparent 70%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .radar-sweep::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top: 3px solid #001F8F; /* Wejha Brand Deep Blue */
            animation: radar-rotate 2s linear infinite;
        }

        @keyframes radar-rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .pulse-active {
            animation: active-glow 2s infinite ease-in-out;
        }

        @keyframes active-glow {
            0% { box-shadow: 0 0 0 0 rgba(0, 31, 143, 0.4); }
            70% { box-shadow: 0 0 0 12px rgba(0, 31, 143, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 31, 143, 0); }
        }

        /* Success & Fail animations */
        .check-anim {
            animation: check-pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes check-pop {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left Side: Camera Scanner Viewport & Controls (2/3 width) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col items-center">
            
            <div class="flex items-center gap-3 w-full justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <div id="scanner-indicator" class="w-2.5 h-2.5 rounded-full bg-gray-400"></div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">كاميرا مسح التذاكر / Active Scanner</h3>
                </div>
                <span class="text-xs text-gray-400">التحضير للفعالية المحددة</span>
            </div>

            <!-- Viewport HUD Container -->
            <div class="scanner-hud-container mb-6 flex items-center justify-center">
                <!-- HUD brackets -->
                <div class="hud-corner hud-tl"></div>
                <div class="hud-corner hud-tr"></div>
                <div class="hud-corner hud-bl"></div>
                <div class="hud-corner hud-br"></div>
                
                <!-- Laser line -->
                <div id="laser" class="laser-line"></div>
                
                <!-- Camera reader target -->
                <div id="reader" class="w-full h-full object-cover"></div>
            </div>

            <!-- Controls Panel -->
            <div class="w-full max-w-md space-y-4">
                
                <!-- Camera Select Dropdown -->
                <div>
                    <label for="camera-select" class="block text-xs font-bold text-gray-500 mb-1.5 dark:text-gray-400">
                        اختر الكاميرا المفضلة / Select Camera Source
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <select id="camera-select" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">جاري تحميل الكاميرات... / Loading cameras...</option>
                        </select>
                    </div>
                </div>

                <!-- Start/Stop Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button id="start-btn" class="bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-800 hover:to-indigo-950 text-white font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        تشغيل الكاميرا / Start
                    </button>
                    <button id="stop-btn" class="bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 dark:from-gray-800 dark:to-gray-700 dark:hover:from-gray-700 dark:hover:to-gray-600 text-gray-700 dark:text-gray-200 font-bold py-3 px-6 rounded-xl text-sm transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        إيقاف الكاميرا / Stop
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-gray-100 dark:border-gray-800"></div>
                    <span class="flex-shrink mx-4 text-gray-400 text-xs">أو التحقق اليدوي / Manual Entry</span>
                    <div class="flex-grow border-t border-gray-100 dark:border-gray-800"></div>
                </div>

                <!-- Manual Input Bar -->
                <div class="flex gap-2">
                    <input type="text" id="manual-hash-input" placeholder="أدخل رمز التذكرة اليدوي..." class="flex-1 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <button id="manual-submit-btn" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-sm">
                        تحقق / Verify
                    </button>
                </div>

            </div>
        </div>

        <!-- Right Side: Status and Results Panel (1/3 width) -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm min-h-[460px] flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                    حالة البطاقة والمسح / Validation Details
                </h3>

                <!-- Idle/Waiting state -->
                <div id="status-container" class="flex flex-col items-center justify-center py-10 rounded-xl border border-dashed border-gray-200 dark:border-gray-800 min-h-[280px]">
                    <div class="radar-sweep mb-6">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 11v.01M5.93 5.93l.71.71M18.07 18.07l.71.71M4 12h1m14 0h1M5.93 18.07l.71-.71M18.07 5.93l.71-.71M12 7a5 5 0 110 10 5 5 0 010-10z"></path></svg>
                    </div>
                    <div id="status-title" class="text-sm font-bold text-gray-700 dark:text-gray-300">في انتظار البطاقة...</div>
                    <div id="status-message" class="text-xs text-gray-400 text-center px-6 mt-2 leading-relaxed">
                        يرجى تقريب رمز الـ QR من عدسة الكاميرا أو إدخال كود التذكرة يدوياً
                    </div>
                </div>

                <!-- Success State Card -->
                <div id="attendee-card" class="hidden bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-200/60 dark:border-emerald-900/40 p-5 rounded-2xl">
                    <div class="flex items-center gap-3 mb-5 text-emerald-700 dark:text-emerald-400">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg check-anim">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="font-bold text-sm">تم تأكيد حضور الطالب / Check-in OK</span>
                    </div>

                    <!-- Attendee avatar circle and details -->
                    <div class="flex flex-col items-center mb-6">
                        <div id="res-avatar" class="w-16 h-16 rounded-full bg-emerald-600/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-bold text-xl flex items-center justify-center mb-3 border border-emerald-200 dark:border-emerald-800">
                            ط
                        </div>
                        <h4 id="res-name" class="text-base font-bold text-gray-800 dark:text-gray-100 text-center">أحمد التويجري</h4>
                    </div>

                    <div class="space-y-3 text-xs border-t border-emerald-100 dark:border-emerald-900/30 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">المدرسة / School</span>
                            <span id="res-school" class="font-bold text-gray-700 dark:text-gray-200">ثانوية الأمير فيصل</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">وقت التحضير / Time</span>
                            <span id="res-time" class="font-bold text-gray-700 dark:text-gray-200">14:22:15</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Back to event button -->
            <div class="mt-6 border-t border-gray-100 dark:border-gray-800 pt-4">
                <a href="{{ \App\Filament\Resources\EventResource::getUrl('edit', ['record' => $record]) }}" class="w-full flex items-center justify-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    العودة لصفحة الفعالية / Back to Event
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts and logics -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html5QrCode = new Html5Qrcode("reader");
            const cameraSelect = document.getElementById('camera-select');
            const startBtn = document.getElementById('start-btn');
            const stopBtn = document.getElementById('stop-btn');
            const laser = document.getElementById('laser');
            const scannerIndicator = document.getElementById('scanner-indicator');
            const statusContainer = document.getElementById('status-container');
            const statusTitle = document.getElementById('status-title');
            const statusMessage = document.getElementById('status-message');
            const attendeeCard = document.getElementById('attendee-card');
            const resName = document.getElementById('res-name');
            const resSchool = document.getElementById('res-school');
            const resTime = document.getElementById('res-time');
            const resAvatar = document.getElementById('res-avatar');
            
            const manualInput = document.getElementById('manual-hash-input');
            const manualSubmitBtn = document.getElementById('manual-submit-btn');

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
                    gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.18);
                } else {
                    osc.frequency.setValueAtTime(140, audioCtx.currentTime); // Low buzz
                    gain.gain.setValueAtTime(0.35, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.45);
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
                        fps: 15,
                        qrbox: { width: 250, height: 250 }
                    },
                    (qrCodeMessage) => {
                        const now = Date.now();
                        // Prevent duplicate scan in 3.5 seconds window
                        if (now - lastScanTime < 3500) return;
                        lastScanTime = now;

                        handleQrCode(qrCodeMessage);
                    },
                    (errorMessage) => {
                        // Suppress logs to maintain high FPS
                    }
                ).then(() => {
                    isScanning = true;
                    laser.style.display = 'block';
                    scannerIndicator.classList.remove('bg-gray-400');
                    scannerIndicator.classList.add('bg-emerald-500', 'pulse-active');
                    
                    statusTitle.innerText = "جاري الكشف... / Scanning...";
                    statusMessage.innerText = "ضع الرمز في منتصف إطار الكاميرا للتحقق التلقائي";
                }).catch(err => {
                    console.error(err);
                });
            });

            stopBtn.addEventListener('click', () => {
                if (!isScanning) return;
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    laser.style.display = 'none';
                    scannerIndicator.classList.remove('bg-emerald-500', 'pulse-active');
                    scannerIndicator.classList.add('bg-gray-400');
                    
                    statusTitle.innerText = "في انتظار البدء... / Standby";
                    statusMessage.innerText = "انقر على تشغيل الكاميرا لبدء فحص رموز التذاكر";
                });
            });

            manualSubmitBtn.addEventListener('click', () => {
                const val = manualInput.value.trim();
                if (!val) return;
                handleQrCode(val);
            });

            function handleQrCode(qrHash) {
                // Update indicator status
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
                        event_id: '{{ $record->id }}', // Enforce specific event ID check!
                        device: 'ماسح الفعالية / Event Scanner'
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
                        
                        // Extract initials for beautiful avatar circle
                        const nameParts = data.attendee.name.split(' ');
                        const initials = nameParts.map(part => part.charAt(0)).slice(0, 2).join(' ');
                        resAvatar.innerText = initials || 'ط';

                        attendeeCard.classList.remove('hidden');
                        manualInput.value = ''; // Reset manual field on success

                        // Reset back to scanning state after 3.5 seconds
                        setTimeout(() => {
                            if (isScanning) {
                                attendeeCard.classList.add('hidden');
                                statusContainer.classList.remove('hidden');
                                statusTitle.innerText = "جاري الكشف... / Scanning...";
                            }
                        }, 3500);
                    } else {
                        playSound('error');
                        statusTitle.innerText = "فشل المسح / Scan Failed";
                        statusMessage.innerText = data.message;
                    }
                })
                .catch(err => {
                    playSound('error');
                    statusTitle.innerText = "خطأ اتصال / Connection Error";
                    statusMessage.innerText = "فشل التحقق بسبب مشكلة في الاتصال بالخادم.";
                });
            }
        });
    </script>
</x-filament-panels::page>
