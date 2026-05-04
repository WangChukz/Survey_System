<div class="w-full">
    <!-- Header Admin - Tối giản -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 pb-6 border-b border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Hệ Thống Quản Trị</h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">Phân tích dữ liệu khảo sát SJT</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.location.reload()" class="p-2 rounded-lg border border-gray-100 text-gray-400 hover:bg-gray-50 hover:text-gray-900 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
            <button class="bg-gray-900 text-white px-6 py-2 rounded-lg text-xs font-bold hover:bg-black transition-all shadow-sm flex items-center">
                Xuất báo cáo
            </button>
        </div>
    </div>

    <!-- Stats Cards Grid - Monochrome Style -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)]">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Tổng sinh viên</p>
            <div class="flex items-end space-x-2">
                <h2 class="text-3xl font-black text-gray-900 leading-none"><?= number_format($generalStats['total_participants']) ?></h2>
                <span class="text-xs font-bold text-gray-400 mb-0.5 pb-0.5">người</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)]">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Hoàn thành</p>
            <div class="flex items-end space-x-2">
                <h2 class="text-3xl font-black text-gray-900 leading-none"><?= number_format($generalStats['completed_attempts']) ?></h2>
                <span class="text-xs font-bold text-gray-400 mb-0.5 pb-0.5">bản ghi</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)]">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Điểm trung bình</p>
            <div class="flex items-end space-x-2">
                <h2 class="text-3xl font-black text-gray-900 leading-none"><?= $generalStats['average_score'] ?></h2>
                <span class="text-xs font-bold text-gray-400 mb-0.5 pb-0.5">/ <?= $generalStats['average_max_score'] ?? '0' ?></span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 items-stretch">
        
        <!-- Faculty Distribution -->
        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)] flex flex-col">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-8">Cơ cấu sinh viên theo Khoa</h3>
            
            <div class="flex-1 flex flex-col md:flex-row items-center justify-center gap-10">
                <div class="w-full md:w-1/2 relative h-[220px]">
                    <canvas id="facultyChart"></canvas>
                </div>
                <div id="facultyLegend" class="grid grid-cols-2 gap-x-4 gap-y-2 w-full md:w-1/2">
                    <!-- Legend populated by JS -->
                </div>
            </div>
        </div>

        <!-- Behavioral Radar -->
        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)] flex flex-col">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-8">Xu hướng hành vi</h3>
            <div class="flex-1 flex items-center justify-center h-[280px]">
                <canvas id="traitsChart"></canvas>
            </div>
        </div>

        <!-- Faculty Score Comparison -->
        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)] lg:col-span-2 flex flex-col">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-8 text-center">So sánh điểm trung bình giữa các Khoa</h3>
            <div class="h-[350px]">
                <canvas id="scoreChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Palette Màu Đơn Sắc (Monochrome) như trang Result
    const monoPalette = ['#111827', '#374151', '#4b5563', '#6b7280', '#9ca3af', '#d1d5db', '#e5e7eb', '#f3f4f6'];

    // 1. Faculty Doughnut
    const facultyData = <?= $facultyChartData ?>;
    new Chart(document.getElementById('facultyChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: facultyData.labels,
            datasets: [{
                data: facultyData.data,
                backgroundColor: monoPalette,
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    const legendContainer = document.getElementById('facultyLegend');
    facultyData.labels.forEach((label, index) => {
        if (index < monoPalette.length) {
            const item = document.createElement('div');
            item.className = 'flex items-center space-x-2';
            item.innerHTML = `
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: ${monoPalette[index]}"></span>
                <span class="text-[10px] font-medium text-gray-500 truncate" title="${label}">${label}</span>
            `;
            legendContainer.appendChild(item);
        }
    });

    // 2. Radar Chart
    const traitsData = <?= $traitsChartData ?>;
    new Chart(document.getElementById('traitsChart').getContext('2d'), {
        type: 'radar',
        data: {
            labels: traitsData.labels,
            datasets: [{
                data: traitsData.data,
                backgroundColor: 'rgba(17, 24, 39, 0.03)',
                borderColor: '#111827',
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#111827',
                borderWidth: 2,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    grid: { color: '#f3f4f6' },
                    pointLabels: { font: { size: 10, family: "'Inter', sans-serif", weight: '600' }, color: '#9ca3af' },
                    ticks: { display: false }
                }
            },
            plugins: { legend: { display: false } }
        }
    });

    // 3. Score Bar Chart
    const scoreData = <?= $scoreChartData ?>;
    new Chart(document.getElementById('scoreChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: scoreData.labels,
            datasets: [{
                data: scoreData.data,
                backgroundColor: '#111827',
                borderRadius: 4,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f9fafb' },
                    ticks: { font: { size: 10 }, color: '#9ca3af' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '600' }, color: '#4b5563' }
                }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
