# 🚌 DATN_FA25_Booking_PolyCoach ## 📖 Giới thiệu **DATN_FA25_Booking_PolyCoach** là hệ thống **đặt vé xe khách trực tuyến** được xây dựng bằng **Laravel Framework**. Mục tiêu của dự án là giúp người dùng dễ dàng tìm kiếm, đặt vé, quản lý chuyến đi, đồng thời hỗ trợ nhà xe và quản trị viên quản lý hệ thống hiệu quả. --- ## 🎭 Các tác nhân (Actors) ### 👤 Khách vãng lai (Guest) - Xem danh sách tuyến và chuyến xe - Tìm kiếm chuyến theo điểm đi – điểm đến – ngày – giờ - Xem giá vé, loại xe, nhà xe - Đăng ký tài khoản để đặt vé ### 👥 Người dùng đã đăng nhập (Customer) - Đặt vé và thanh toán trực tuyến - Hủy / đổi vé - Xem lịch sử đặt vé - Đánh giá chuyến xe ### 🛠️ Quản trị viên (Admin) - Quản lý người dùng, nhà xe, tuyến, chuyến, vé, khuyến mãi - Duyệt đăng ký nhà xe mới - Theo dõi thống kê hệ thống và doanh thu --- ## 🧩 Công nghệ sử dụng - **Framework:** Laravel 11 - **Ngôn ngữ:** PHP 8.x, JavaScript (ES6) - **Giao diện:** Blade Template + Bootstrap 5 - **Cơ sở dữ liệu:** MySQL - **Công cụ quản lý:** Git, GitHub - **Server local:** Laragon / XAMPP --- ## ⚙️ Cách cài đặt ### 1️⃣ Clone dự án
bash
git clone https://github.com/FPLkhanhtnph49665/DATN_FA25_Booking_PolyCoach.git

composer install
npm install

DB_DATABASE=booking_polycoach
DB_USERNAME=root
DB_PASSWORD=

php artisan migrate --seed

php artisan ser

---

Muốn mình thêm luôn **mục lộ trình phát triển (Roadmap)** và **chức năng hoàn thành / đang làm** (có checkbox ✅ ⏳) cho nó chuyên nghiệp hơn không?  
Ví dụ:
markdown ## 🚀 Roadmap - [x] Xây dựng cấu trúc Laravel - [x] Quản lý tuyến xe - [x] Quản lý chuyến xe - [ ] Thanh toán online - [ ] Báo cáo doanh thu
