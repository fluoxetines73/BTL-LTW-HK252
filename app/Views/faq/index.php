<?php
/**
 * @var string $pageTitle
 * @var array  $grouped
 * @var int    $total
 */
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2"><?= htmlspecialchars($pageTitle) ?></h1>
                <span class="badge" style="background-color: #E71A0F; color: white;">Có <?= (int)$total ?> câu hỏi thường gặp</span>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="faq-search" class="form-control" placeholder="Tìm kiếm câu hỏi...">
                </div>
            </div>

            <?php if (empty($grouped)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Chưa có câu hỏi nào.
                </div>
            <?php else: ?>
                <?php foreach ($grouped as $category => $faqs): ?>
                    <div class="faq-category-group mb-4">
                        <h3 class="h5 mb-3" style="color: #E71A0F;">
                            <i class="bi bi-folder2-open"></i> <?= htmlspecialchars($category) ?>
                        </h3>

                        <div class="accordion accordion-flush" id="faq-accordion-<?= md5($category) ?>">
                            <?php foreach ($faqs as $index => $faq): ?>
                                <?php
                                $uniqueId = 'faq-' . $faq['id'];
                                $isFirst = $index === 0;
                                ?>
                                <div class="accordion-item" data-faq-question="<?= htmlspecialchars($faq['question']) ?>">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $isFirst ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $uniqueId ?>" aria-expanded="<?= $isFirst ? 'true' : 'false' ?>" aria-controls="<?= $uniqueId ?>">
                                            <?= htmlspecialchars($faq['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="<?= $uniqueId ?>" class="accordion-collapse collapse <?= $isFirst ? 'show' : '' ?>" data-bs-parent="#faq-accordion-<?= md5($category) ?>">
                                        <div class="accordion-body">
                                            <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('faq-search');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            document.querySelectorAll('.accordion-item').forEach(function(item) {
                const question = item.getAttribute('data-faq-question') || '';
                const answerText = item.querySelector('.accordion-body')?.textContent || '';
                const combinedText = (question + ' ' + answerText).toLowerCase();

                item.style.display = combinedText.includes(query) ? '' : 'none';
            });

            document.querySelectorAll('.faq-category-group').forEach(function(group) {
                const visibleItems = Array.from(group.querySelectorAll('.accordion-item')).filter(item => item.style.display !== 'none');
                group.style.display = visibleItems.length > 0 ? '' : 'none';
            });
        });
    }
});
</script>
