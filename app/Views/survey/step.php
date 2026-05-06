<div class="mb-8 border-b border-gray-100 pb-5">
    <p class="text-sm text-gray-500 mt-1">Vui lòng chọn đáp án phản ánh đúng nhất hành vi thực tế của bạn.</p>
</div>

<form action="<?= BASE_URL ?>/survey/submit" method="POST" class="space-y-8">
    <input type="hidden" name="batch_id" value="<?= htmlspecialchars($batchId ?? '') ?>">
    <input type="hidden" name="response_time_ms" value="15000"> <!-- Giả lập JS tracking -->

    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $index => $q): ?>
            <div class="bg-gray-50/50 rounded-xl p-5 border border-gray-100 transition-colors hover:border-gray-200">
                <p class="font-medium text-gray-900 mb-5 leading-relaxed text-[15px]">
                    <span class="text-gray-400 mr-1"><?= $index + 1 ?>.</span> 
                    <?= htmlspecialchars($q['content']) ?>
                </p>
                
                <div class="space-y-2.5">
                    <?php foreach ($q['options'] as $opt): ?>
                        <label class="flex items-start space-x-3 p-3.5 rounded-lg border border-transparent hover:bg-white hover:shadow-sm hover:border-gray-200 cursor-pointer transition-all duration-200 group">
                            <div class="flex-shrink-0 mt-0.5">
                                <?php if ($q['question_type'] === 'SC'): ?>
                                    <input type="radio" 
                                        name="answers[<?= $q['id'] ?>]" 
                                        value="<?= $opt['id'] ?>" 
                                        required
                                        class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-900 cursor-pointer transition-colors">
                                <?php else: ?>
                                    <input type="checkbox" 
                                        name="answers[<?= $q['id'] ?>][]" 
                                        value="<?= $opt['id'] ?>"
                                        class="w-4 h-4 text-gray-900 rounded border-gray-300 focus:ring-gray-900 cursor-pointer transition-colors">
                                <?php endif; ?>
                            </div>
                            <span class="text-gray-600 text-sm group-hover:text-gray-900 transition-colors leading-snug pt-0.5">
                                <?= htmlspecialchars($opt['option_text']) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="pt-6 flex justify-end">
        <button type="submit" class="px-8 bg-gray-900 hover:bg-black text-white font-medium py-3 rounded-lg shadow-[0_4px_14px_0_rgb(0,0,0,0.1)] transition-all duration-200 transform hover:-translate-y-0.5">
            Tiếp tục →
        </button>
    </div>
</form>
