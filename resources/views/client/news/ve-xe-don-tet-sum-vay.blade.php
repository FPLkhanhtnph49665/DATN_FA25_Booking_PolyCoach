@extends('layouts.client')

@section('title', 'Vé xe đón Tết sum vầy')

@section('content')

<section class="container my-5">
    <div class="row g-4">

        <!-- NỘI DUNG CHÍNH -->
        <div class="col-lg-8">
            <article class="article-card">

                <h1 class="article-title">
                    Vé Xe Đón Tết Sum Vầy – Hành Trình Trở Về Nhà Đầy Yêu Thương
                </h1>

                <div class="article-meta">
                    <span>📅 20/12/2025</span>
                    <span>•</span>
                    <span>Tin tức</span>
                </div>

                <img
                    src="https://via.placeholder.com/900x420?text=Ve+Xe+Don+Tet"
                    class="article-cover"
                    alt="Vé xe đón Tết"
                >

                <div class="article-content">
                    <p>
                        Tết Nguyên Đán là thời khắc thiêng liêng nhất trong năm, khi mỗi người con xa quê đều mong mỏi
                        được trở về nhà, sum vầy bên gia đình sau một năm dài mưu sinh.
                    </p>

                    <p>
                        Vé xe về quê đón Tết không chỉ là phương tiện di chuyển, mà còn là chiếc cầu nối đưa yêu thương
                        trở về với mái ấm thân quen.
                    </p>

                    <p>
                        Việc đặt vé sớm giúp hành khách chủ động lịch trình, tránh tình trạng quá tải và đảm bảo
                        một chuyến đi an toàn, thoải mái.
                    </p>

                    <div class="article-alert">
                        <strong>Lời khuyên:</strong>
                        Nên đặt vé trước từ 2–4 tuần để đảm bảo có chỗ ngồi tốt nhất.
                    </div>
                </div>

            </article>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <!-- Tin liên quan -->
            <aside class="sidebar-card">
                <h5 class="sidebar-title">Tin liên quan</h5>

                <ul class="related-list">
                    <li>
                        <a href="#">Kinh nghiệm đặt vé xe dịp Tết</a>
                    </li>
                    <li>
                        <a href="#">Những tuyến xe đông khách cuối năm</a>
                    </li>
                </ul>
            </aside>

            <!-- CTA -->
            <aside class="sidebar-card cta-card">
                <h5 class="sidebar-title">Đặt vé xe ngay</h5>
                <p>
                    Chủ động đặt vé sớm để có chuyến đi an toàn và trọn vẹn bên gia đình.
                </p>
                <a href="#" class="btn btn-danger w-100">
                    Đặt vé ngay
                </a>
            </aside>

        </div>
    </div>
</section>

@endsection
