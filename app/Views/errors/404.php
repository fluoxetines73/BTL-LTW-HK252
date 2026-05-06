<style>
.error-page {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
}

.error-content {
    max-width: 500px;
}

.error-icon {
    font-size: 5rem;
    color: #E71A0F;
    margin-bottom: 1rem;
}

.error-code {
    font-size: 8rem;
    font-weight: 700;
    color: #E71A0F;
    line-height: 1;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.error-title {
    font-size: 1.75rem;
    color: #333;
    margin-bottom: 1rem;
}

.error-message {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.btn-home {
    background-color: #E71A0F;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.2s;
    display: inline-block;
}

.btn-home:hover {
    background-color: #c4150b;
    color: white;
}

.error-actions {
    margin-top: 1.5rem;
}

.error-actions a {
    color: #E71A0F;
    text-decoration: none;
    margin: 0 0.5rem;
}

.error-actions a:hover {
    text-decoration: underline;
}
</style>

<section class="error-page">
    <div class="error-content">
        <div class="error-code">404</div>
        <h1 class="error-title">Không tìm thấy trang</h1>
        <p class="error-message">
            Rất tiếc, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
            Vui lòng kiểm tra lại đường dẫn hoặc quay về trang chủ.
        </p>
        <a href="<?= BASE_URL ?>" class="btn-home">
            <i class="fa-solid fa-house"></i> Về trang chủ
        </a>
        <div class="error-actions">
            <a href="javascript:history.back()"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            <a href="<?= BASE_URL ?>home/contact"><i class="fa-solid fa-envelope"></i> Liên hệ hỗ trợ</a>
        </div>
    </div>
</section>
