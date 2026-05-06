<div class="text-center mb-10">
    <!-- Icon Success -->
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-5">
        <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Kết quả của <?= htmlspecialchars($participant['fullname'] ?? 'Sinh viên') ?></h1>
    <p class="text-gray-400 mb-6 text-xs uppercase tracking-widest font-semibold">
        <?= htmlspecialchars($participant['faculty'] ?? 'Khoa chưa xác định') ?>
    </p>
    
    <!-- Badge Phân loại -->
    <div class="inline-block px-6 py-3 rounded-xl bg-gray-50 border border-gray-100">
        <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase block mb-1">Phong cách nổi bật</span>
        <span class="text-lg font-black text-gray-900"><?= htmlspecialchars($groupName ?? 'Chưa phân loại') ?></span>
    </div>
</div>

<!-- Grid Layout cho 3 Biểu đồ (3 cột) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 items-stretch">
    
    <!-- Cột 1: Biểu đồ Tròn (Doughnut) -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-1 uppercase tracking-wide">Tỷ Trọng Quyết Định</h3>
            <p class="text-xs text-gray-500">Phân bổ lựa chọn theo 4 nhóm hành vi</p>
        </div>
        <div class="flex-1 flex flex-col justify-center">
            <div class="w-full relative flex items-center justify-center mb-6" style="height: 200px;">
                <canvas id="doughnutChart"></canvas>
            </div>
            <!-- Custom Legend 2 Cột -->
            <div class="grid grid-cols-2 gap-x-2 gap-y-2 px-2">
                <?php 
                $colors = ['#111827', '#4b5563', '#9ca3af', '#e5e7eb'];
                $i = 0;
                foreach (($doughnutData ?? []) as $label => $val): 
                ?>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: <?= $colors[$i++] ?>;"></span>
                    <span class="text-[10px] font-medium text-gray-600 truncate" title="<?= htmlspecialchars($label) ?>">
                        <?= htmlspecialchars($label) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Cột 2: Đồng hồ đo (Gauge) -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-1 uppercase tracking-wide">Tổng Điểm Kỷ Luật</h3>
            <p class="text-xs text-gray-500">Mức độ tuân thủ tiêu chuẩn đạo đức</p>
        </div>
        <div class="flex-1 flex flex-col justify-center relative">
            <div class="w-full relative flex items-center justify-center" style="height: 200px;">
                <canvas id="gaugeChart"></canvas>
                <!-- Điểm số nằm giữa vòng cung -->
                <div class="absolute bottom-2 text-center">
                    <span class="text-5xl font-extrabold text-gray-900 leading-none"><?= $totalScore ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột 3: Biểu đồ Radar -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-1 uppercase tracking-wide">Chỉ Số Chi Tiết</h3>
            <p class="text-xs text-gray-500">Sự ưu tiên giữa các loại chi phí ẩn</p>
        </div>
        <div class="flex-1 flex items-center justify-center" style="height: 250px;">
            <canvas id="radarChart"></canvas>
        </div>
    </div>
</div>

<!-- Thanh Phần Trăm (Progress Bars) -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-10">
    <h3 class="text-sm font-semibold text-gray-900 mb-1 uppercase tracking-wide">Tỷ Lệ Tương Đồng & Phân Tích Lý Do</h3>
    <p class="text-xs text-gray-500 mb-6">Click vào từng nhóm để xem chi tiết các lý do dẫn đến kết quả này</p>
    
    <div class="space-y-4">
        <?php foreach (($similarityData ?? []) as $index => $group): ?>
        <div class="border border-gray-50 rounded-xl overflow-hidden">
            <!-- Thanh chính (Trigger) -->
            <button onclick="toggleAccordion(<?= $index ?>)" class="w-full text-left p-4 hover:bg-gray-50 transition-colors focus:outline-none">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($group['name']) ?></span>
                        <svg id="icon-<?= $index ?>" class="w-4 h-4 ml-2 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-900"><?= $group['percentage'] ?>%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-gray-900 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: <?= $group['percentage'] ?>%"></div>
                </div>
            </button>

            <!-- Nội dung chi tiết (Accordion Content) -->
            <div id="content-<?= $index ?>" class="hidden bg-gray-50/50 px-6 pb-6 pt-2 border-t border-gray-50">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Phân tích lý do cấu thành:</h4>
                <div class="space-y-4">
                    <?php foreach ($group['reasons'] as $reasonName => $reasonScore): ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600"><?= htmlspecialchars($reasonName) ?></span>
                            <span class="text-xs font-medium text-gray-500"><?= $reasonScore ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gray-400 h-1.5 rounded-full" style="width: <?= $reasonScore ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Nút thao tác -->
<div class="text-center mt-6 flex justify-center space-x-4">
    <a href="<?= BASE_URL ?>/" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Quay lại
    </a>
    <a href="#" onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Lưu báo cáo
    </a>
</div>

<script>
// Hàm xử lý Accordion
function toggleAccordion(index) {
    const content = document.getElementById(`content-${index}`);
    const icon = document.getElementById(`icon-${index}`);
    
    // Đóng tất cả các cái khác (Tùy chọn: nếu muốn chỉ mở 1 cái 1 lúc)
    // const allContents = document.querySelectorAll('[id^="content-"]');
    // allContents.forEach(c => { if(c.id !== `content-${index}`) c.classList.add('hidden'); });

    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    
    // Bảng màu Minimalist
    const colors = ['#111827', '#4b5563', '#9ca3af', '#e5e7eb'];
    
    // 1. Khởi tạo Doughnut Chart (Phân bổ quyết định)
    const doughnutData = <?= json_encode($doughnutData ?? []) ?>;
    if (Object.keys(doughnutData).length > 0) {
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: Object.keys(doughnutData),
                datasets: [{
                    data: Object.values(doughnutData),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false // Ẩn legend mặc định để dùng HTML legend 2 cột
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.raw}%` }
                    }
                }
            }
        });
    }

    // 2. Khởi tạo Gauge Chart (Tổng điểm - Half Doughnut)
    const totalScore = <?= $totalScore ?? 0 ?>;
    const maxScore = <?= $maxScore ?? 100 ?>;
    const ctxGauge = document.getElementById('gaugeChart').getContext('2d');
    new Chart(ctxGauge, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [totalScore, Math.max(0, maxScore - totalScore)],
                backgroundColor: ['#111827', '#f3f4f6'],
                borderWidth: 0,
                circumference: 180, // Chỉ vẽ nửa vòng
                rotation: 270       // Xoay lên trên
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false } // Vô hiệu hóa tooltip vì đã có số ở giữa
            }
        }
    });

    // 3. Khởi tạo Radar Chart
    const radarData = <?= json_encode($radarData ?? []) ?>;
    if (Object.keys(radarData).length > 0) {
        const ctxRadar = document.getElementById('radarChart').getContext('2d');
        new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: Object.keys(radarData),
                datasets: [{
                    label: 'Điểm mức độ',
                    data: Object.values(radarData),
                    backgroundColor: 'rgba(17, 24, 39, 0.05)',
                    borderColor: 'rgba(17, 24, 39, 0.8)',
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'rgba(17, 24, 39, 1)',
                    borderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    r: {
                        ticks: { display: false, min: 0, max: 10 },
                        pointLabels: { font: { family: "'Inter', sans-serif", size: 11 }, color: '#6b7280' }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
