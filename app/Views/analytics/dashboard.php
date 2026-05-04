<?php
/**
 * View: analytics/dashboard — Dashboard phân tích kết quả khảo sát.
 *
 * Biến nhận được:
 *   $survey         — Survey
 *   $totalResponses — int
 *   $charts         — array<int, array>   (question_id → chart data)
 *   $insights       — array<int, array[]> (question_id → insight list)
 */
?>

<section class="analytics-header">
    <div class="analytics-meta">
        <a href="<?= BASE_URL ?>/" class="back-link">← Trang chủ</a>
        <a href="<?= BASE_URL ?>/survey/<?= (int) $survey->id ?>" class="btn btn-ghost btn-sm">
            Tham gia khảo sát
        </a>
    </div>
    <h1 class="page-title">Kết quả: <?= htmlspecialchars((string) $survey->title) ?></h1>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?= number_format($totalResponses) ?></span>
            <span class="stat-label">Lượt tham gia</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= count($charts) ?></span>
            <span class="stat-label">Câu hỏi</span>
        </div>
    </div>
</section>

<?php if (empty($charts)): ?>
    <div class="empty-state">
        <span class="empty-icon">📊</span>
        <p>Chưa có dữ liệu phân tích. Hãy chờ người dùng tham gia khảo sát.</p>
    </div>
<?php else: ?>
    <div class="charts-grid">
        <?php foreach ($charts as $qId => $chart): ?>
            <div class="chart-card" id="chart-card-<?= (int) $qId ?>">

                <!-- Question header -->
                <div class="chart-card__header">
                    <h2 class="chart-card__title">
                        <?= htmlspecialchars($chart['question_label']) ?>
                    </h2>
                    <div class="chart-card__meta">
                        <span class="badge badge-type"><?= htmlspecialchars($chart['question_type']) ?></span>
                        <span class="badge"><?= $chart['answer_count'] ?> câu trả lời</span>
                        <?php if ($chart['skip_rate'] > 0): ?>
                            <span class="badge badge-warning">Bỏ qua: <?= $chart['skip_rate'] ?>%</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Insights -->
                <?php if (!empty($insights[$qId])): ?>
                    <div class="insight-list">
                        <?php foreach ($insights[$qId] as $insight): ?>
                            <div class="insight-item insight-item--<?= htmlspecialchars($insight['type']) ?>">
                                <?= htmlspecialchars($insight['message']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Chart body -->
                <div class="chart-card__body">
                    <?php if ($chart['chartType'] === 'text_list'): ?>
                        <!-- Câu hỏi text: hiển thị danh sách câu trả lời -->
                        <ul class="text-answers-list">
                            <?php foreach (array_slice($chart['data'], 0, 10) as $textAnswer): ?>
                                <li class="text-answer-item">
                                    "<?= htmlspecialchars($textAnswer) ?>"
                                </li>
                            <?php endforeach; ?>
                            <?php if (count($chart['data']) > 10): ?>
                                <li class="text-answer-more">
                                    ... và <?= count($chart['data']) - 10 ?> câu trả lời khác
                                </li>
                            <?php endif; ?>
                        </ul>

                    <?php else: ?>
                        <!-- Câu hỏi choice/rating: render chart.js -->
                        <canvas id="chart-<?= (int) $qId ?>" class="chart-canvas"></canvas>

                        <?php if (!empty($chart['stats'])): ?>
                            <div class="chart-stats">
                                <span>Trung bình: <strong><?= $chart['stats']['mean'] ?></strong></span>
                                <span>Trung vị: <strong><?= $chart['stats']['median'] ?></strong></span>
                                <span>Min: <strong><?= $chart['stats']['min'] ?></strong></span>
                                <span>Max: <strong><?= $chart['stats']['max'] ?></strong></span>
                            </div>
                        <?php endif; ?>

                        <script>
                        (function() {
                            const ctx = document.getElementById('chart-<?= (int) $qId ?>');
                            if (!ctx) return;
                            new Chart(ctx, {
                                type: '<?= htmlspecialchars($chart['chartType']) ?>',
                                data: <?= json_encode($chart['data'], JSON_UNESCAPED_UNICODE) ?>,
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { position: 'bottom' }
                                    }
                                }
                            });
                        })();
                        </script>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
