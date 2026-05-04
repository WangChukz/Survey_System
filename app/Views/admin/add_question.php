<div class="<?= htmlspecialchars($maxWidth ?? 'max-w-4xl') ?> mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Thêm Câu Hỏi Mới</h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">Quản lý ngân hàng câu hỏi khảo sát</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/admin" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="bg-gray-900 border border-black text-white px-5 py-4 rounded-xl mb-8 flex items-center shadow-lg animate-fade-in-down">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        <span class="text-sm font-medium tracking-wide">Đã lưu câu hỏi và đáp án thành công vào hệ thống.</span>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= BASE_URL ?>/admin/questions/store" method="POST" class="bg-white rounded-2xl p-8 border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)]">
        
        <!-- Khối Thông tin chung Câu hỏi -->
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-6 flex items-center border-b border-gray-100 pb-3">
            <i class="fas fa-cube mr-2 text-gray-400"></i> Cấu trúc câu hỏi
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Custom Select cho Nhóm câu hỏi -->
            <div class="custom-select relative" data-name="batch_code">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Nhóm câu hỏi (Batch Code)</label>
                <input type="hidden" name="batch_code" value="1">
                <button type="button" class="select-btn w-full bg-gray-50 border-0 rounded-xl shadow-sm focus:ring-2 focus:ring-gray-900 p-3 text-sm font-medium text-gray-700 text-left flex justify-between items-center transition-all">
                    <span class="selected-text">Nhóm 1 (Khởi động)</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200"></i>
                </button>
                <ul class="options-list absolute z-20 w-full bg-white border border-gray-100 rounded-xl shadow-xl mt-2 hidden overflow-x-hidden opacity-0 transform -translate-y-2 transition-all duration-200 max-h-96 overflow-y-auto">
                    <li data-value="1" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors font-bold bg-gray-50">Nhóm 1 (Khởi động)</li>
                    <li data-value="2A" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Nhóm 2A</li>
                    <li data-value="2B" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Nhóm 2B</li>
                    <li data-value="3A1" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Nhóm 3A1</li>
                    <li data-value="3A2" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Nhóm 3A2</li>
                    <li data-value="3B1" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Nhóm 3B1</li>
                    <li data-value="3B2" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Nhóm 3B2</li>
                </ul>
            </div>
            
            <!-- Custom Select cho Loại câu hỏi -->
            <div class="custom-select relative" data-name="question_type">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Loại câu hỏi</label>
                <input type="hidden" name="question_type" value="SC">
                <button type="button" class="select-btn w-full bg-gray-50 border-0 rounded-xl shadow-sm focus:ring-2 focus:ring-gray-900 p-3 text-sm font-medium text-gray-700 text-left flex justify-between items-center transition-all">
                    <span class="selected-text">Single Choice (Chỉ chọn 1)</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200"></i>
                </button>
                <ul class="options-list absolute z-20 w-full bg-white border border-gray-100 rounded-xl shadow-xl mt-2 hidden overflow-x-hidden opacity-0 transform -translate-y-2 transition-all duration-200 max-h-96 overflow-y-auto">
                    <li data-value="SC" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors font-bold bg-gray-50">Single Choice (Chỉ chọn 1)</li>
                    <li data-value="MC" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Multiple Choice (Chọn nhiều)</li>
                    <li data-value="Likert" class="option-item px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Likert Scale (Thang đo)</li>
                </ul>
            </div>
        </div>

        <div class="mb-10">
            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Nội dung câu hỏi</label>
            <textarea name="content" rows="3" class="w-full bg-gray-50 border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-gray-900 p-4 text-sm text-gray-800 font-medium placeholder-gray-400" placeholder="Ví dụ: Q01: Tại trạm xe, một người đang chỉ sai đường cho khách. Bạn sẽ làm gì?" required></textarea>
        </div>

        <!-- Khối Đáp án -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-3 gap-4">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide flex items-center">
                <i class="fas fa-list-ul mr-2 text-gray-400"></i> Các lựa chọn đáp án
            </h2>
            <button type="button" id="add-option-btn" class="bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Thêm lựa chọn
            </button>
        </div>

        <div id="options-container" class="space-y-3 mb-10">
            <!-- Dòng đáp án mẫu (mặc định hiện 2 dòng) -->
            <?php for($i = 0; $i < 2; $i++): ?>
            <div class="option-row flex flex-col md:flex-row gap-3 items-start bg-white p-2 rounded-xl border border-gray-100 shadow-sm relative group hover:border-gray-300 transition-colors">
                <div class="w-full md:flex-grow">
                    <input type="text" name="option_text[]" class="w-full bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-gray-900 p-3 text-sm text-gray-800 placeholder-gray-400" placeholder="Nhập nội dung đáp án..." required>
                </div>
                <div class="w-full md:w-28">
                    <input type="number" step="0.5" name="option_points[]" class="w-full bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-gray-900 p-3 text-sm text-gray-800 text-center placeholder-gray-400" placeholder="Điểm số" required>
                </div>
                <div class="w-full md:w-40">
                    <input type="text" name="option_cost_tag[]" class="w-full bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-gray-900 p-3 text-sm text-gray-800 placeholder-gray-400" placeholder="Nhãn (VD: Vị kỷ)" required>
                </div>
                <button type="button" class="remove-option text-gray-400 hover:text-red-500 p-3 transition-colors md:self-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Submit -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-gray-900 hover:bg-black text-white text-sm font-bold py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Hoàn tất thêm câu hỏi
            </button>
        </div>
    </form>
</div>

<!-- JavaScript để Thêm/Xóa Form Đáp Án Động -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('options-container');
    const addBtn = document.getElementById('add-option-btn');

    // Template HTML cho 1 dòng đáp án mới
    const optionTemplate = `
        <div class="option-row flex flex-col md:flex-row gap-3 items-start bg-white p-2 rounded-xl border border-gray-100 shadow-sm relative group hover:border-gray-300 transition-colors animate-fade-in-down">
            <div class="w-full md:flex-grow">
                <input type="text" name="option_text[]" class="w-full bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-gray-900 p-3 text-sm text-gray-800 placeholder-gray-400" placeholder="Nhập nội dung đáp án..." required>
            </div>
            <div class="w-full md:w-28">
                <input type="number" step="0.5" name="option_points[]" class="w-full bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-gray-900 p-3 text-sm text-gray-800 text-center placeholder-gray-400" placeholder="Điểm số" required>
            </div>
            <div class="w-full md:w-40">
                <input type="text" name="option_cost_tag[]" class="w-full bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-gray-900 p-3 text-sm text-gray-800 placeholder-gray-400" placeholder="Nhãn (VD: Vị kỷ)" required>
            </div>
            <button type="button" class="remove-option text-gray-400 hover:text-red-500 p-3 transition-colors md:self-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    // Thêm dòng
    addBtn.addEventListener('click', function() {
        container.insertAdjacentHTML('beforeend', optionTemplate);
    });

    // Xóa dòng (Dùng Event Delegation)
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            const row = e.target.closest('.option-row');
            // Yêu cầu giữ lại ít nhất 2 đáp án
            if (container.querySelectorAll('.option-row').length > 2) {
                row.remove();
            } else {
                alert("Một câu hỏi cần tối thiểu 2 đáp án!");
            }
        }
    });

    // --------------------------------------------------------
    // Script cho Custom Select (Dropdown bo tròn)
    // --------------------------------------------------------
    const customSelects = document.querySelectorAll('.custom-select');

    customSelects.forEach(select => {
        const btn = select.querySelector('.select-btn');
        const list = select.querySelector('.options-list');
        const items = select.querySelectorAll('.option-item');
        const hiddenInput = select.querySelector('input[type="hidden"]');
        const selectedText = select.querySelector('.selected-text');
        const icon = select.querySelector('.fa-chevron-down');

        // Toggle dropdown
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Đóng các dropdown khác đang mở
            document.querySelectorAll('.options-list:not(.hidden)').forEach(otherList => {
                if (otherList !== list) {
                    closeDropdown(otherList.closest('.custom-select'));
                }
            });

            const isExpanded = !list.classList.contains('hidden');
            if (isExpanded) {
                closeDropdown(select);
            } else {
                openDropdown(select);
            }
        });

        // Click vào option
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                
                // Cập nhật giá trị
                const value = item.getAttribute('data-value');
                const text = item.textContent;
                hiddenInput.value = value;
                selectedText.textContent = text;

                // Cập nhật style cho option được chọn
                items.forEach(i => {
                    i.classList.remove('font-bold', 'bg-gray-50');
                });
                item.classList.add('font-bold', 'bg-gray-50');

                closeDropdown(select);
            });
        });
    });

    // Click ra ngoài để đóng dropdown
    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select').forEach(select => {
            closeDropdown(select);
        });
    });

    function openDropdown(select) {
        const list = select.querySelector('.options-list');
        const icon = select.querySelector('.fa-chevron-down');
        
        list.classList.remove('hidden');
        // Kích hoạt transition
        setTimeout(() => {
            list.classList.remove('opacity-0', '-translate-y-2');
            list.classList.add('opacity-100', 'translate-y-0');
        }, 10);
        icon.classList.add('rotate-180');
    }

    function closeDropdown(select) {
        const list = select.querySelector('.options-list');
        const icon = select.querySelector('.fa-chevron-down');
        
        list.classList.remove('opacity-100', 'translate-y-0');
        list.classList.add('opacity-0', '-translate-y-2');
        icon.classList.remove('rotate-180');
        
        // Đợi transition xong mới ẩn đi
        setTimeout(() => {
            if(list.classList.contains('opacity-0')) {
                list.classList.add('hidden');
            }
        }, 200);
    }
});
</script>

<style>
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
    /* Ẩn scrollbar hoàn toàn nhưng vẫn cho phép cuộn */
    .options-list {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    .options-list::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }
</style>
