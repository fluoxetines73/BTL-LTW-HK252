<?php if (!empty($page) && !empty($page['content'])): ?>
<?= $page['content'] ?>
<?php else: ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Điều Khoản Sử Dụng</h1>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">1. Chấp Nhận Điều Khoản</h5>
                    <p>Bằng cách sử dụng website hoặc ứng dụng CGV Cinema, bạn đồng ý tuân thủ các Điều Khoản Sử Dụng này. Nếu bạn không đồng ý, vui lòng không sử dụng dịch vụ của chúng tôi. CGV Cinema có quyền thay đổi các điều khoản này bất kỳ lúc nào.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">2. Tài Khoản Người Dùng</h5>
                    <p>Khi tạo tài khoản, bạn phải cung cấp thông tin chính xác và cập nhật. Bạn chịu trách nhiệm bảo mật mật khẩu của mình và tất cả hoạt động xảy ra dưới tài khoản của bạn. Bạn phải thông báo cho chúng tôi ngay lập tức nếu phát hiện truy cập trái phép.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">3. Vé và Đặt Chỗ</h5>
                    <p>Vé được cung cấp trên cơ sở "còn sẵn có". Giá vé có thể thay đổi mà không cần thông báo trước. Vé không chuyển nhượng được và chỉ có giá trị cho suất chiếu được chỉ định. Bạn không được phép bán lại vé hoặc sử dụng vé cho mục đích thương mại.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">4. Chính Sách Hoàn Tiền</h5>
                    <p>Vé không hoàn tiền sau khi được mua. Tuy nhiên, bạn có thể đổi vé sang một suất chiếu khác miễn phí nếu còn chỗ trống và yêu cầu được thực hiện trước ít nhất 1 giờ trước suất chiếu gốc.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">5. Thanh Toán</h5>
                    <p>Tất cả thanh toán phải được thực hiện trước khi vé được phát hành. Chúng tôi chấp nhận các thẻ tín dụng chính, chuyển khoản ngân hàng, và các phương thức thanh toán điện tử khác. Bạn chịu trách nhiệm về mọi khoản phí ngân hàng liên quan.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">6. Hành Vi của Khách Hàng</h5>
                    <p>Bạn đồng ý không: (1) quay phim hoặc chụp ảnh bộ phim; (2) mang thức ăn từ bên ngoài vào rạp; (3) gây phiền hà hoặc làm phiền khách hàng khác; (4) sử dụng điện thoại trong phòng chiếu; (5) vi phạm các quy định khác của rạp.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">7. Trách Nhiệm Hạn Chế</h5>
                    <p>CGV Cinema KHÔNG chịu trách nhiệm về: (1) những sự cố kỹ thuật hoặc mất điện; (2) thất thoát hoặc hư hỏng tài sản cá nhân; (3) thương tích cá nhân; (4) những mất mát gián tiếp hoặc không dự tính. Bạn sử dụng dịch vụ của chúng tôi với rủi ro của riêng bạn.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">8. Quyền Bản Quyền</h5>
                    <p>Tất cả nội dung trên website CGV Cinema, bao gồm văn bản, hình ảnh, video, và logo, được bảo vệ bởi quyền tác giả. Bạn không được phép sao chép, phân phối, hoặc sửa đổi nội dung này mà không có sự cho phép bằng văn bản.</p>
                </div>
            </div>

            <p class="text-muted mt-4">Những Điều Khoản Sử Dụng này có hiệu lực từ tháng 1 năm 2025. Chúng tôi sẽ thông báo cho bạn về bất kỳ thay đổi nào thông qua trang web hoặc email.</p>

            <div class="mt-4">
                <a href="<?= BASE_URL ?>home" class="btn btn-secondary">← Quay lại Trang Chủ</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>