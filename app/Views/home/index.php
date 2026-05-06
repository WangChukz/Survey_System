<?php $hasError = isset($_SESSION['error']); ?>

<div class="w-full">
    <!-- Phần Giới thiệu chung -->
    <div class="text-center mb-8">
        <img src="<?= BASE_URL ?>/assets/logo.svg" alt="SJT Logo" class="w-20 h-20 mx-auto mb-4 object-contain">
        <h1 class="text-2xl font-bold text-gray-900 mb-2 uppercase leading-tight">
            Công cụ đánh giá phản ứng cá nhân<br/>trong các tình huống trách nhiệm xã hội
        </h1>
        <p class="text-gray-500 text-base italic mt-2">Situational Judgement Test (SJT)</p>
    </div>

    <!-- Bảng thông tin khảo sát -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <table class="w-full text-sm text-left text-gray-700">
            <tbody>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3 font-semibold bg-gray-50 w-1/3">Trường</td>
                    <td class="px-4 py-3">Nhóm sinh viên Học viện Ngân hàng</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3 font-semibold bg-gray-50">Đơn vị / Khoa</td>
                    <td class="px-4 py-3">ITDE</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3 font-semibold bg-gray-50">Mục đích sử dụng</td>
                    <td class="px-4 py-3">Đánh giá xu hướng hành vi trong tình huống trách nhiệm xã hội</td>
                </tr>
                <!-- <tr class="border-b border-gray-200">
                    <td class="px-4 py-3 font-semibold bg-gray-50">Phiên bản</td>
                    <td class="px-4 py-3">1.0</td>
                </tr> -->
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3 font-semibold bg-gray-50">Ngôn ngữ</td>
                    <td class="px-4 py-3">Tiếng Việt</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3 font-semibold bg-gray-50">Số câu</td>
                    <td class="px-4 py-3">15 câu</td>
                </tr>
                <tr>
                    <td class="px-4 py-3 font-semibold bg-gray-50">Thời gian dự kiến</td>
                    <td class="px-4 py-3">15-25 phút</td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if ($hasError): ?>
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-medium"><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
            

        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Nút Bắt Đầu Khảo Sát & Giới thiệu (Ẩn đi khi form mở hoặc khi có lỗi) -->
    <div id="start-section" class="flex flex-col sm:flex-row justify-center gap-4 mb-8 <?= $hasError ? 'hidden' : '' ?>">
        <button type="button" onclick="showForm()" 
            class="bg-gray-900 hover:bg-black text-white font-medium py-2.5 px-6 text-sm rounded-lg shadow-sm transition-all duration-200 transform hover:-translate-y-0.5 inline-flex justify-center items-center">
            Bắt đầu khảo sát
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
        <button type="button" onclick="toggleIntro()" 
            class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-6 text-sm rounded-lg border border-gray-200 shadow-sm transition-all duration-200 inline-flex justify-center items-center">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Giới thiệu
        </button>
    </div>

    <!-- Phần Giới thiệu chi tiết (Mặc định ẩn) -->
    <div id="intro-content" class="hidden bg-white p-8 rounded-xl border border-gray-200 shadow-sm mb-8 text-gray-700 leading-relaxed text-sm text-justify">
        
        <div class="mb-8">
            <h3 class="font-bold text-lg mb-3 text-gray-900 flex items-center gap-2">
                <i class="fas fa-bullseye text-indigo-600"></i> I. Giới thiệu phương pháp SJT
            </h3>
            <p class="mb-3">
                <strong>Situational Judgment Test (SJT)</strong> là phương pháp đánh giá tâm lý học hiện đại được ứng dụng rộng rãi trong đo lường năng lực và dự đoán hành vi. Khác với các bài trắc nghiệm tính cách truyền thống (tự đánh giá), SJT đặt bạn vào <strong>những tình huống thực tế có độ khó cao</strong> mang tính tiến thoái lưỡng nan, yêu cầu bạn phải đưa ra quyết định hành động.
            </p>
            <p>
                Hệ thống SJT này được thiết kế chuyên biệt để phân tích <strong>xu hướng phản ứng và trách nhiệm xã hội</strong> trong bối cảnh học đường và cộng đồng. Sẽ không có đáp án "đúng" hay "sai" tuyệt đối, mỗi lựa chọn đều phản ánh trung thực một hệ giá trị mà bạn đang ưu tiên.
            </p>
        </div>

        <div class="mb-8">
            <h3 class="font-bold text-lg mb-4 text-gray-900 flex items-center gap-2">
                <i class="fas fa-cogs text-indigo-600"></i> II. Cơ chế vận hành
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cơ chế thích ứng -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-2 flex items-center gap-2"><i class="fas fa-code-branch text-gray-500"></i> 1. Phân nhánh thích ứng (Adaptive)</h4>
                    <p class="text-sm text-gray-600 text-justify">Hệ thống gồm 35 câu hỏi được chia thành 7 lô. Dựa trên phản ứng của bạn ở lô khởi động, AI sẽ tự động phân tích và điều hướng bạn vào các lô tình huống tiếp theo có độ phức tạp tăng dần (Trách nhiệm cao hoặc Trách nhiệm thấp).</p>
                </div>
                <!-- Cơ chế chi phí -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-2 flex items-center gap-2"><i class="fas fa-tags text-gray-500"></i> 2. Phân tích chi phí (Cost-Tags)</h4>
                    <p class="text-sm text-gray-600 text-justify">Đằng sau mỗi lựa chọn là một sự đánh đổi (Trade-off). Bạn sẽ phải "trả giá" bằng <em>Thời gian, Hình ảnh cá nhân, Tiền bạc, hoặc Mối quan hệ</em>. Dữ liệu này giúp hệ thống nội suy ra động cơ thực sự đằng sau hành vi của bạn.</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-lg mb-4 text-gray-900 flex items-center gap-2">
                <i class="fas fa-users text-indigo-600"></i> III. 4 Nhóm hành vi (Archetypes)
            </h3>
            <p class="mb-4">Kết thúc bài test, hệ thống sẽ phân loại bạn vào 1 trong 4 nhóm xu hướng hành vi cốt lõi:</p>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="mt-1 w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></div>
                    <div>
                        <strong class="text-gray-900">Trách nhiệm Cao - Chủ động:</strong> Sẵn sàng dấn thân, hành động giải quyết vấn đề mà không cần chờ đợi yêu cầu, dám chịu thiệt thòi vì lợi ích chung.
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-1 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                    <div>
                        <strong class="text-gray-900">Trách nhiệm Cao - Thận trọng:</strong> Có ý thức cộng đồng tốt nhưng thường cân nhắc kỹ lưỡng, thu thập thông tin và bảo vệ sự an toàn trước khi hành động.
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-1 w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></div>
                    <div>
                        <strong class="text-gray-900">Trách nhiệm Thấp - Thụ động:</strong> Thường mang tâm lý quan sát, chờ đợi người khác làm trước hoặc bị ảnh hưởng bởi đám đông (Hiệu ứng người ngoài cuộc).
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-1 w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
                    <div>
                        <strong class="text-gray-900">Trách nhiệm Thấp - Ưu tiên cá nhân:</strong> Đặt sự tiện lợi, an toàn và lợi ích cá nhân lên trên hết. Thường có lý luận sắc bén để bảo vệ quyền lợi bản thân.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Nhập Thông Tin (Mặc định ẩn, trừ khi có lỗi) -->
    <div id="personal-info-form" class="bg-gray-50 p-6 rounded-xl border border-gray-100 <?= $hasError ? '' : 'hidden' ?>">
        <h2 class="text-lg font-bold text-gray-900 mb-4 text-center">Nhập thông tin cá nhân</h2>
        <form id="surveyForm" action="<?= BASE_URL ?>/survey/start" method="POST" class="space-y-4">
            <div>
                <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" required autocomplete="off"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-colors bg-white text-gray-900 shadow-sm">
                <p id="error-fullname" class="text-red-500 text-xs mt-1.5 hidden"><i class="fas fa-exclamation-circle mr-1"></i>Vui lòng nhập họ và tên</p>
            </div>

            <div class="custom-select relative" data-name="faculty">
                <label class="block text-sm font-medium text-gray-700 mb-1">Khoa</label>
                <input type="hidden" name="faculty" id="faculty-hidden" required>
                <button type="button" class="select-btn w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-all bg-white text-gray-900 shadow-sm flex justify-between items-center text-sm">
                    <span class="selected-text text-gray-400">-- Chọn khoa của bạn --</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <ul class="options-list absolute z-30 w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-2 hidden overflow-x-hidden opacity-0 transform -translate-y-2 transition-all duration-200 max-h-60 overflow-y-auto">
                    <li data-value="Khoa công nghệ thông tin và Kinh tế số" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Công nghệ thông tin và Kinh tế số</li>
                    <li data-value="Khoa Tài Chính" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Tài Chính</li>
                    <li data-value="Khoa Ngân Hàng" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Ngân Hàng</li>
                    <li data-value="Khoa Kế toán - Kiểm toán" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Kế toán - Kiểm toán</li>
                    <li data-value="Khoa Quản trị kinh doanh" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Quản trị kinh doanh</li>
                    <li data-value="Khoa Kinh doanh quốc tế" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Kinh doanh quốc tế</li>
                    <li data-value="Khoa Luật Kinh tế" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Luật Kinh tế</li>
                    <li data-value="Khoa Ngoại ngữ" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0">Khoa Ngoại ngữ</li>
                    <li data-value="Khoa Kinh tế" class="option-item px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">Khoa Kinh tế</li>
                </ul>
                <p id="error-faculty" class="text-red-500 text-xs mt-1.5 hidden"><i class="fas fa-exclamation-circle mr-1"></i>Vui lòng chọn Khoa/Đơn vị của bạn</p>
            </div>
            
            <div>
                <label for="student_code" class="block text-sm font-medium text-gray-700 mb-1">Mã sinh viên</label>
                <input type="text" id="student_code" name="student_code" required autocomplete="off"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-colors bg-white text-gray-900 shadow-sm">
                <p id="error-student-code" class="text-red-500 text-xs mt-1.5 hidden"><i class="fas fa-exclamation-circle mr-1"></i>Vui lòng nhập mã sinh viên hợp lệ</p>
            </div>

            <button type="submit" 
                class="w-full mt-2 bg-gray-900 hover:bg-black text-white font-medium py-2.5 text-sm rounded-lg shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
                Vào làm bài
            </button>
        </form>
    </div>
</div>

<script>
function showForm() {
    document.getElementById('start-section').classList.add('hidden');
    document.getElementById('intro-content').classList.add('hidden');
    
    const formSection = document.getElementById('personal-info-form');
    formSection.classList.remove('hidden');
    
    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    setTimeout(() => {
        document.getElementById('fullname').focus();
    }, 300);
}

function toggleIntro() {
    const introContent = document.getElementById('intro-content');
    introContent.classList.toggle('hidden');
}

// Custom Dropdown Logic
document.addEventListener('DOMContentLoaded', function() {
    const customSelects = document.querySelectorAll('.custom-select');

    customSelects.forEach(select => {
        const btn = select.querySelector('.select-btn');
        const list = select.querySelector('.options-list');
        const items = select.querySelectorAll('.option-item');
        const hiddenInput = select.querySelector('input[type="hidden"]');
        const selectedText = select.querySelector('.selected-text');
        const icon = select.querySelector('svg');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isExpanded = !list.classList.contains('hidden');
            if (isExpanded) {
                closeDropdown(select);
            } else {
                openDropdown(select);
            }
        });

        items.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const value = item.getAttribute('data-value');
                const text = item.textContent;
                hiddenInput.value = value;
                selectedText.textContent = text;
                selectedText.classList.remove('text-gray-400');
                selectedText.classList.add('text-gray-900');

                items.forEach(i => i.classList.remove('bg-gray-50', 'font-bold'));
                item.classList.add('bg-gray-50', 'font-bold');

                closeDropdown(select);
            });
        });
    });

    document.addEventListener('click', () => {
        customSelects.forEach(select => closeDropdown(select));
    });

    function openDropdown(select) {
        const list = select.querySelector('.options-list');
        const icon = select.querySelector('svg');
        list.classList.remove('hidden');
        setTimeout(() => {
            list.classList.remove('opacity-0', '-translate-y-2');
            list.classList.add('opacity-100', 'translate-y-0');
        }, 10);
        icon.classList.add('rotate-180');
    }

    function closeDropdown(select) {
        const list = select.querySelector('.options-list');
        const icon = select.querySelector('svg');
        list.classList.remove('opacity-100', 'translate-y-0');
        list.classList.add('opacity-0', '-translate-y-2');
        icon.classList.remove('rotate-180');
        setTimeout(() => {
            if(list.classList.contains('opacity-0')) {
                list.classList.add('hidden');
            }
        }, 200);
    }
});

// Auto format Họ tên (xóa khoảng trắng thừa) khi rời khỏi ô nhập
document.getElementById('fullname').addEventListener('blur', function() {
    if (this.value) {
        // Xóa khoảng trắng 2 đầu và thay thế nhiều khoảng trắng ở giữa bằng 1 khoảng trắng
        this.value = this.value.trim().replace(/\s+/g, ' ');
    }
});

// Form Validation Logic
document.getElementById('surveyForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Ngăn submit mặc định để validate trước
    let isValid = true;
    
    const fullname = document.getElementById('fullname');
    
    // Auto format lại một lần nữa trước khi gửi
    if (fullname.value) {
        fullname.value = fullname.value.trim().replace(/\s+/g, ' ');
    }
    const studentCode = document.getElementById('student_code');
    const faculty = document.getElementById('faculty-hidden');
    const facultyBtn = document.querySelector('.select-btn');
    
    // Hàm hiển thị lỗi
    const showError = (inputEl, errorId) => {
        inputEl.classList.remove('border-gray-200', 'focus:ring-gray-200', 'focus:border-gray-400');
        inputEl.classList.add('border-red-500', 'focus:ring-red-200', 'focus:border-red-500');
        document.getElementById(errorId).classList.remove('hidden');
        isValid = false;
    };

    // Hàm xóa lỗi
    const clearError = (inputEl, errorId) => {
        inputEl.classList.add('border-gray-200', 'focus:ring-gray-200', 'focus:border-gray-400');
        inputEl.classList.remove('border-red-500', 'focus:ring-red-200', 'focus:border-red-500');
        document.getElementById(errorId).classList.add('hidden');
    };

    // Reset tất cả lỗi
    clearError(fullname, 'error-fullname');
    clearError(facultyBtn, 'error-faculty');
    clearError(studentCode, 'error-student-code');

    // 1. Validate Họ tên
    if (!fullname.value.trim()) {
        showError(fullname, 'error-fullname');
    } else if (fullname.value.trim().length < 2) {
        document.getElementById('error-fullname').innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Họ tên quá ngắn';
        showError(fullname, 'error-fullname');
    }

    // 2. Validate Khoa
    if (!faculty.value.trim()) {
        showError(facultyBtn, 'error-faculty');
    }

    // 3. Validate Mã sinh viên
    const studentCodeVal = studentCode.value.trim();
    if (!studentCodeVal) {
        showError(studentCode, 'error-student-code');
    } else if (studentCodeVal.length < 5) {
        document.getElementById('error-student-code').innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Mã sinh viên không hợp lệ';
        showError(studentCode, 'error-student-code');
    } else if (!/^[a-zA-Z0-9]+$/.test(studentCodeVal)) {
        document.getElementById('error-student-code').innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Mã sinh viên không chứa ký tự đặc biệt';
        showError(studentCode, 'error-student-code');
    }

    if (isValid) {
        this.submit();
    }
});
</script>

<style>
    /* Ẩn scrollbar hoàn toàn nhưng vẫn cho phép cuộn */
    .options-list {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .options-list::-webkit-scrollbar {
        display: none;
    }
</style>
