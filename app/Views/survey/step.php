<div class="mb-8 border-b border-gray-100 pb-5">
    <p class="text-sm text-gray-500 mt-1">Vui lòng chọn đáp án phản ánh đúng nhất hành vi thực tế của bạn.</p>
</div>

<form action="<?= BASE_URL ?>/survey/submit" method="POST" class="space-y-8">
    <input type="hidden" name="batch_id" value="<?= htmlspecialchars($batchId ?? '') ?>">
    <input type="hidden" name="response_time_ms" value="15000"> <!-- Giả lập JS tracking -->

    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $q): ?>
            <?= $q->renderWithWrapper() ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="pt-6 flex justify-end">
        <button type="submit" class="px-8 bg-gray-900 hover:bg-black text-white font-medium py-3 rounded-lg shadow-[0_4px_14px_0_rgb(0,0,0,0.1)] transition-all duration-200 transform hover:-translate-y-0.5">
            Tiếp tục →
        </button>
    </div>
</form>
