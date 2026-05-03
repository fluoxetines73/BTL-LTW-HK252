<?php
// Check if structured data is available
$hasStructuredData = !empty($settings);

// Fallback to default content if no database data
if (!$hasStructuredData):
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Static fallback content -->
<section class="about-hero text-center" data-aos="fade-in">
    <div class="container">
        <span class="about-hero-kicker">Thông tin & hỗ trợ</span>
        <h1 class="display-4 fw-bold">Về CJ CGV Việt Nam <i class="fa-solid fa-clapperboard"></i></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giới thiệu</li>
            </ol>
        </nav>
    </div>
</section>

<section class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-6" data-aos="fade-right">
            <div class="about-content">
                <h2 class="mb-4" style="color: #E71A0F;">Tổ hợp Văn hóa - Cultureplex</h2>
                <p><strong>CJ CGV</strong> là một trong top 5 cụm rạp chiếu phim lớn nhất toàn cầu và là nhà phát hành, cụm rạp chiếu phim lớn nhất Việt Nam. Chúng tôi tự hào là đơn vị tiên phong mang đến khái niệm độc đáo <strong>Cultureplex</strong> (Tổ hợp Văn hóa), nơi khán giả không chỉ đến để xem phim mà còn để trải nghiệm các dịch vụ giải trí, ẩm thực và mua sắm đẳng cấp.</p>
                <p>Tại Việt Nam, CGV luôn nỗ lực xây dựng các chương trình Trách nhiệm xã hội như <em>"Điện ảnh cho mọi ngườii"</em>, <em>"Dự án phim ngắn CJ"</em> nhằm đồng hành và đóng góp cho sự phát triển chung của nền công nghiệp điện ảnh nước nhà.</p>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <div class="text-center p-4">
                <div class="about-img bg-light rounded shadow overflow-hidden" style="height: 420px;">
                    <img src="<?= BASE_URL ?>public/images/about/about-6.png" alt="Giới thiệu CJ CGV Việt Nam" class="about-feature-image">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Hành trình phát triển</h2>
        <div class="timeline">
            <div class="timeline-item left" data-aos="fade-right">
                <div class="timeline-content">
                    <h3 style="color: #E71A0F;">2011</h3>
                    <p>CJ Group (Hàn Quốc) chính thức tiếp quản hệ thống rạp MegaStar tại Việt Nam, đặt nền móng đầu tiên cho hành trình nâng tầm trải nghiệm điện ảnh tiêu chuẩn quốc tế.</p>
                </div>
            </div>
            <div class="timeline-item right" data-aos="fade-left">
                <div class="timeline-content">
                    <h3 style="color: #E71A0F;">2014</h3>
                    <p>Chính thức chuyển đổi thương hiệu thành <strong>CGV Cinemas</strong>. Ra mắt công nghệ chiếu phim 4DX và IMAX lần đầu tiên tại Việt Nam, tạo nên cơn sốt phòng vé.</p>
                </div>
            </div>
            <div class="timeline-item left" data-aos="fade-right">
                <div class="timeline-content">
                    <h3 style="color: #E71A0F;">2017 - 2019</h3>
                    <p>Giới thiệu công nghệ ScreenX – phòng chiếu phim đa diện đầu tiên, và Starium Laser. Liên tục ra mắt các phòng chiếu cao cấp như Gold Class, L'Amour, Premium.</p>
                </div>
            </div>
            <div class="timeline-item right" data-aos="fade-left">
                <div class="timeline-content">
                    <h3 style="color: #E71A0F;">Hiện nay</h3>
                    <p>Khẳng định vị thế nhà phát hành và cụm rạp số 1 Việt Nam với mạng lưới phủ sóng khắp các tỉnh thành, trở thành điểm đến văn hóa quen thuộc của hàng triệu khán giả.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4">
        <div class="col-md-6" data-aos="zoom-in-up" data-aos-delay="100">
            <div class="vision-mission-card text-center h-100 p-4 border rounded shadow-sm">
                <span class="vision-icon fs-1 mb-3 d-block"><i class="fa-solid fa-globe" style="color: var(--cgv-red);"></i></span>
                <h3 style="color: #E71A0F;">Tầm nhìn</h3>
                <p>Trở thành công ty giải trí và truyền thông phong cách sống toàn cầu (Global Lifestyle Entertainment Company). CGV hướng tới việc tạo ra những giá trị vượt ra ngoài những giới hạn của rạp chiếu phim truyền thống.</p>
            </div>
        </div>
        <div class="col-md-6" data-aos="zoom-in-up" data-aos-delay="200">
            <div class="vision-mission-card text-center h-100 p-4 border rounded shadow-sm">
                <span class="vision-icon fs-1 mb-3 d-block"><i class="fa-solid fa-bullseye" style="color: var(--cgv-red);"></i></span>
                <h3 style="color: #E71A0F;">Sứ mệnh</h3>
                <p>Tiên phong mang đến những công nghệ điện ảnh hiện đại nhất thế giới. Không ngừng hỗ trợ các nhà làm phim trẻ và đóng góp vào sự phát triển mạnh mẽ của nền điện ảnh Việt Nam.</p>
            </div>
        </div>
    </div>
</section>

<section class="counter-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100">
                <div class="counter-box">
                    <h3 class="display-4 fw-bold">80+</h3>
                    <p>Cụm rạp toàn quốc</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                <div class="counter-box">
                    <h3 class="display-4 fw-bold">470+</h3>
                    <p>Phòng chiếu hiện đại</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="300">
                <div class="counter-box">
                    <h3 class="display-4 fw-bold">30+</h3>
                    <p>Tỉnh / Thành phố</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="400">
                <div class="counter-box">
                    <h3 class="display-4 fw-bold">#1</h3>
                    <p>Thị phần tại Việt Nam</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <h2 class="text-center mb-5" data-aos="fade-up">Giá trị nổi bật</h2>
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4" data-aos="flip-left" data-aos-delay="100">
            <div class="core-values-item text-center">
                <div class="value-icon fs-1 mb-3"><i class="fa-solid fa-film"></i></div>
                <h4 style="color: #E71A0F;">Công nghệ đỉnh cao</h4>
                <p>Độc quyền mang đến các định dạng chiếu phim tiên tiến nhất như IMAX, 4DX, ScreenX, Starium mang lại trải nghiệm nhập vai hoàn hảo.</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4" data-aos="flip-left" data-aos-delay="200">
            <div class="core-values-item text-center">
                <div class="value-icon fs-1 mb-3"><i class="fa-solid fa-shapes"></i></div>
                <h4 style="color: #E71A0F;">Cultureplex</h4>
                <p>Mô hình Tổ hợp văn hóa kết hợp đa dạng các dịch vụ mua sắm, ăn uống và giải trí ngay trong cùng một không gian rạp chiếu.</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4" data-aos="flip-left" data-aos-delay="300">
            <div class="core-values-item text-center">
                <div class="value-icon fs-1 mb-3"><i class="fa-solid fa-seedling"></i></div>
                <h4 style="color: #E71A0F;">Nuôi dưỡng tài năng</h4>
                <p>Tổ chức và tài trợ các cuộc thi phim ngắn, lớp học làm phim nhằm ươm mầm cho các đạo diễn, biên kịch trẻ của Việt Nam.</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4" data-aos="flip-left" data-aos-delay="400">
            <div class="core-values-item text-center">
                <div class="value-icon fs-1 mb-3"><i class="fa-solid fa-heart"></i></div>
                <h4 style="color: #E71A0F;">Trách nhiệm xã hội</h4>
                <p>Cam kết tổ chức định kỳ các chương trình chiếu phim miễn phí cho trẻ em vùng sâu, vùng xa có hoàn cảnh khó khăn.</p>
            </div>
        </div>
    </div>
</section>

<section class="team-section">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Đội ngũ lãnh đạo</h2>
        <div class="row">
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="team-card team-card-retired text-center">
                    <div class="team-avatar fs-1 team-avatar-1">
                        <i class="fa-solid fa-user-tie"></i>
                        <span class="team-retired-mark" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </div>
                    <h5 class="mt-3 mb-1">Nguyễn Tấn Đạt</h5>
                    <span class="team-status-badge team-status-retired">Retired</span>
                    <p class="text-muted mb-0">CEO & Founder</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="team-card text-center">
                    <div class="team-avatar fs-1 team-avatar-2"><i class="fa-solid fa-user-tie"></i></div>
                    <h5 class="mt-3">Nguyễn Thành Danh</h5>
                    <p class="text-muted mb-0">Giám đốc vận hành</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="team-card text-center">
                    <div class="team-avatar fs-1 team-avatar-3"><i class="fa-solid fa-user-secret"></i></div>
                    <h5 class="mt-3">Nguyễn Nhất Duy</h5>
                    <p class="text-muted mb-0">Giám đốc kỹ thuật</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                <div class="team-card text-center">
                    <div class="team-avatar fs-1 team-avatar-4"><i class="fa-solid fa-user-secret"></i></div>
                    <h5 class="mt-3">Hồ Bá Khang</h5>
                    <p class="text-muted mb-0">Giám đốc sáng tạo</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php else: ?>
<!-- STRUCTURED DATA MODE - Using data from database -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Hero Section -->
<section class="about-hero text-center" data-aos="fade-in">
    <div class="container">
        <span class="about-hero-kicker"><?= htmlspecialchars($settings['hero_kicker']) ?></span>
        <h1 class="display-4 fw-bold"><?= htmlspecialchars($settings['hero_title']) ?> <i class="fa-solid fa-clapperboard"></i></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giới thiệu</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Introduction Section -->
<section class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-6" data-aos="fade-right">
            <div class="about-content">
                <h2 class="mb-4" style="color: #E71A0F;"><?= htmlspecialchars($settings['intro_heading']) ?></h2>
                <p><?= $settings['intro_paragraph_1'] ?></p>
                <p><?= $settings['intro_paragraph_2'] ?></p>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <div class="text-center p-4">
                <div class="about-img bg-light rounded shadow overflow-hidden" style="height: 420px;">
                    <img src="<?= BASE_URL . $settings['intro_image'] ?>" alt="<?= htmlspecialchars($settings['hero_title']) ?>" class="about-feature-image">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<?php if (!empty($timelineItems)): ?>
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Hành trình phát triển</h2>
        <div class="timeline">
            <?php foreach ($timelineItems as $index => $item): 
                $aosAnimation = $index % 2 === 0 ? 'fade-right' : 'fade-left';
            ?>
            <div class="timeline-item <?= $index % 2 === 0 ? 'left' : 'right' ?>" data-aos="<?= $aosAnimation ?>">
                <div class="timeline-content">
                    <h3 style="color: #E71A0F;"><?= htmlspecialchars($item['year_label']) ?></h3>
                    <p><?= $item['content'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Vision & Mission Section -->
<section class="container py-5">
    <div class="row g-4">
        <div class="col-md-6" data-aos="zoom-in-up" data-aos-delay="100">
            <div class="vision-mission-card text-center h-100 p-4 border rounded shadow-sm">
                <span class="vision-icon fs-1 mb-3 d-block">
                    <i class="<?= $settings['vision_icon'] ?>" style="color: var(--cgv-red);"></i>
                </span>
                <h3 style="color: #E71A0F;"><?= htmlspecialchars($settings['vision_title']) ?></h3>
                <p><?= $settings['vision_content'] ?></p>
            </div>
        </div>
        <div class="col-md-6" data-aos="zoom-in-up" data-aos-delay="200">
            <div class="vision-mission-card text-center h-100 p-4 border rounded shadow-sm">
                <span class="vision-icon fs-1 mb-3 d-block">
                    <i class="<?= $settings['mission_icon'] ?>" style="color: var(--cgv-red);"></i>
                </span>
                <h3 style="color: #E71A0F;"><?= htmlspecialchars($settings['mission_title']) ?></h3>
                <p><?= $settings['mission_content'] ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<?php if (!empty($statistics)): ?>
<section class="counter-section py-5">
    <div class="container">
        <div class="row text-center">
            <?php foreach ($statistics as $index => $stat): ?>
            <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                <div class="counter-box">
                    <h3 class="display-4 fw-bold"><?= htmlspecialchars($stat['number_display']) ?></h3>
                    <p><?= htmlspecialchars($stat['label']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Core Values Section -->
<?php if (!empty($coreValues)): ?>
<section class="container py-5">
    <h2 class="text-center mb-5" data-aos="fade-up">Giá trị nổi bật</h2>
    <div class="row">
        <?php foreach ($coreValues as $index => $value): ?>
        <div class="col-md-3 col-sm-6 mb-4" data-aos="flip-left" data-aos-delay="<?= ($index + 1) * 100 ?>">
            <div class="core-values-item text-center">
                <div class="value-icon fs-1 mb-3"><i class="<?= $value['icon_class'] ?>"></i></div>
                <h4 style="color: #E71A0F;"><?= htmlspecialchars($value['title']) ?></h4>
                <p><?= $value['description'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Leadership Section -->
<?php if (!empty($leadership)): ?>
<section class="team-section">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Đội ngũ lãnh đạo</h2>
        <div class="row">
            <?php foreach ($leadership as $index => $member): 
                $avatarClass = 'team-avatar-' . (($index % 4) + 1);
            ?>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                <div class="team-card <?= $member['status'] === 'retired' ? 'team-card-retired' : '' ?> text-center">
                    <div class="team-avatar fs-1 <?= $avatarClass ?>">
                        <?php if ($member['avatar_type'] === 'image' && $member['avatar_value']): ?>
                            <img src="<?= BASE_URL . $member['avatar_value'] ?>" alt="<?= htmlspecialchars($member['name']) ?>" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                            <i class="<?= $member['avatar_value'] ?>"></i>
                        <?php endif; ?>
                        <?php if ($member['status'] === 'retired'): ?>
                            <span class="team-retired-mark" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                        <?php endif; ?>
                    </div>
                    <h5 class="mt-3 <?= $member['status'] === 'retired' ? 'mb-1' : '' ?>"><?= htmlspecialchars($member['name']) ?></h5>
                    <?php if ($member['status'] === 'retired'): ?>
                        <span class="team-status-badge team-status-retired">Retired</span>
                    <?php endif; ?>
                    <p class="text-muted mb-0"><?= htmlspecialchars($member['role']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endif; ?>
