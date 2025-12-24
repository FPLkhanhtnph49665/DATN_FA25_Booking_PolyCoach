<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use App\Models\Route;
use App\Models\Bus;
use App\Models\Trip;

use App\Models\PickupDropoffPoint;
use App\Models\News;

use App\Models\PickupPoint;
use App\Models\DropoffPoint;
use App\Models\PointFare;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // 0. Seed Cities
        // =========================
        $cityData = [
            ['name' => 'Hà Nội', 'code' => 'HN'],
            ['name' => 'TP. Hồ Chí Minh', 'code' => 'HCM'],
            ['name' => 'Đà Nẵng', 'code' => 'DN'],
            ['name' => 'Hải Phòng', 'code' => 'HP'],
            ['name' => 'Cần Thơ', 'code' => 'CT'],
            ['name' => 'Nha Trang', 'code' => 'NT'],
            ['name' => 'Huế', 'code' => 'HUE'],
            ['name' => 'Vinh', 'code' => 'VINH'],
            ['name' => 'Buôn Ma Thuột', 'code' => 'BMT'],
            ['name' => 'Đà Lạt', 'code' => 'DL'],
        ];

        $cities = collect();
        foreach ($cityData as $data) {
            $cities[$data['code']] = City::updateOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name'], 'status' => 1]
            );
        }

        // =========================
        // 1. Seed Users
        // =========================
        $users = [
            ['email' => 'admin@polycoach.test',   'role' => 'admin',   'first_name' => 'Super', 'last_name' => 'Admin'],
            ['email' => 'staff@polycoach.test',   'role' => 'staff',   'first_name' => 'Test',  'last_name' => 'Staff'],
            ['email' => 'checker@polycoach.test', 'role' => 'checker', 'first_name' => 'Test',  'last_name' => 'Checker'],
            ['email' => 'user@polycoach.test',    'role' => 'user',    'first_name' => 'Test',  'last_name' => 'User'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'user_code' => 'DATN_FA25_PoLyCoach_' . ucfirst($u['role']) . '_4953',
                    'first_name'=> $u['first_name'],
                    'last_name' => $u['last_name'],
                    'full_name' => $u['first_name'] . ' ' . $u['last_name'],
                    'password'  => Hash::make('1'),
                    'role'      => $u['role'],
                    'status'    => 1,
                ]
            );
        }

        User::factory()->count(2)->create(); // thêm vài user random
        // Admin cố định
        User::updateOrCreate(
            ['email' => 'admin@polycoach.test'],
            [
                'user_code'  => 'DATN_FA25_PoLyCoach_Admin_4953',
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'full_name'  => 'Super Admin',
                'password'   => Hash::make('1'), // 🔐
                'role'       => 'admin',
                'status'     => 1,
            ]
        );

        // User cố định
        User::updateOrCreate(
            ['email' => 'user@polycoach.test'],
            [
                'user_code'  => 'DATN_FA25_PoLyCoach_User_4953',
                'first_name' => 'Test',
                'last_name'  => 'User',
                'full_name'  => 'Test User',
                'password'   => Hash::make('1'),
                'role'       => 'user',
                'status'     => 1,
            ]
        );
        User::updateOrCreate(
            ['email' => 'staff@polycoach.test'],
            [
                'user_code'  => 'DATN_FA25_PoLyCoach_Staff_4953',
                'first_name' => 'Test',
                'last_name'  => 'Staff',
                'full_name'  => 'Test Staff',
                'password'   => Hash::make('1'),
                'role'       => 'staff',
                'status'     => 1,
            ]
        );
        User::updateOrCreate(
            ['email' => 'checker@polycoach.test'],
            [
                'user_code'  => 'DATN_FA25_PoLyCoach_Checker_4953',
                'first_name' => 'Test',
                'last_name'  => 'Checker',
                'full_name'  => 'Test Checker',
                'password'   => Hash::make('1'),
                'role'       => 'checker',
                'status'     => 1,
            ]
        );

        // Thêm 3 admin random (factory của ông đang dùng field gì thì giữ nguyên)
        User::factory()
            ->count(1)
            ->state(['role' => 'admin'])
            ->create();

        // Thêm 5 user random
        User::factory()
            ->count(1)
            ->state(['role' => 'user'])
            ->create();
        // =====================================
        // 2. Seed Routes (from_city_id / to_city_id)
        // =====================================
        // =========================
        // 2. Seed Routes
        // =========================
        $routeData = [
            ['from' => 'HN', 'to' => 'HCM', 'distance' => 1700, 'estimated_time' => '22:00:00'],
            ['from' => 'HCM','to' => 'HN', 'distance' => 1700, 'estimated_time' => '23:00:00'],
            ['from' => 'HN', 'to' => 'DN', 'distance' => 800,  'estimated_time' => '15:00:00'],
            ['from' => 'DN', 'to' => 'HCM','distance' => 960,  'estimated_time' => '18:00:00'],
            ['from' => 'HCM','to' => 'CT', 'distance' => 170,  'estimated_time' => '04:00:00'],
            ['from' => 'HCM','to' => 'NT', 'distance' => 430,  'estimated_time' => '09:00:00'],
            ['from' => 'HCM','to' => 'DL', 'distance' => 300,  'estimated_time' => '08:00:00'],
            ['from' => 'HN', 'to' => 'HP', 'distance' => 120,  'estimated_time' => '03:00:00'],
            ['from' => 'HN', 'to' => 'VINH','distance' => 300,  'estimated_time' => '06:00:00'],
            ['from' => 'HUE','to' => 'DN', 'distance' => 100,  'estimated_time' => '02:30:00'],
        ];

        $routes = collect();
        foreach ($routeData as $data) {
            $fromCity = $cities[$data['from']] ?? null;
            $toCity   = $cities[$data['to']] ?? null;
            if (!$fromCity || !$toCity) continue;

            $route = Route::updateOrCreate(
                ['from_city_id' => $fromCity->id, 'to_city_id' => $toCity->id],
                ['distance' => $data['distance'], 'estimated_time' => $data['estimated_time'], 'status' => 1]
            );

            $routes->push($route);
        }

        // =========================
        // 3. Seed Buses
        // =========================
        $busData = [
            ['plate_number'=>'29B-88888','seat_count'=>32,'type'=>'sleeper','status'=>1],
            ['plate_number'=>'51B-12345','seat_count'=>32,'type'=>'sleeper','status'=>1],
            ['plate_number'=>'43B-54953','seat_count'=>32,'type'=>'limousine','status'=>1],
            ['plate_number'=>'29A-34953','seat_count'=>32,'type'=>'seat','status'=>1],
            ['plate_number'=>'29A-44953','seat_count'=>32,'type'=>'seat','status'=>0],
        ];

        $buses = collect();
        foreach ($busData as $data) {
            $buses->push(
                Bus::updateOrCreate(
                    ['plate_number'=>$data['plate_number']],
                    ['seat_count'=>$data['seat_count'],'type'=>$data['type'],'status'=>$data['status']]
                )
            );
        }

        // =========================
        // 4. Seed Trips
        // =========================
        if ($routes->isNotEmpty() && $buses->isNotEmpty()) {
            Trip::factory()->count(20)->state(function () use ($routes, $buses) {
                return [
                    'route_id' => $routes->random()->id,
                    'bus_id'   => $buses->random()->id,
                ];
            })->create();
        }

        // =========================
        // 5. Seed Pickup / Dropoff Points
        // =========================
        foreach ($routes as $route) {
            $fromCity = $route->fromCity;
            $toCity   = $route->toCity;

            // Pickup Points
            PickupPoint::updateOrCreate(
                ['route_id'=>$route->id,'name'=>'Bến xe '.$fromCity->name],
                ['address'=>'Bến xe trung tâm '.$fromCity->name,'order'=>1]
            );
            PickupPoint::updateOrCreate(
                ['route_id'=>$route->id,'name'=>'Văn phòng '.$fromCity->name],
                ['address'=>'Văn phòng PoLyCoach tại '.$fromCity->name,'order'=>2]
            );

            // Dropoff Points
            DropoffPoint::updateOrCreate(
                ['route_id'=>$route->id,'name'=>'Bến xe '.$toCity->name],
                ['address'=>'Bến xe trung tâm '.$toCity->name,'order'=>1]
            );
            DropoffPoint::updateOrCreate(
                ['route_id'=>$route->id,'name'=>'Văn phòng '.$toCity->name],
                ['address'=>'Văn phòng PoLyCoach tại '.$toCity->name,'order'=>2]
            );
        }

        // =========================
        // 6. Seed Point Fares (giá vé giữa các điểm)
        // =========================
        $pickupPoints = PickupPoint::all();
        $dropoffPoints = DropoffPoint::all();

        foreach ($pickupPoints as $pickup) {
            foreach ($dropoffPoints->where('route_id',$pickup->route_id) as $dropoff) {
                PointFare::updateOrCreate(
                    ['route_id'=>$pickup->route_id,'pickup_point_id'=>$pickup->id,'dropoff_point_id'=>$dropoff->id],
                    ['price'=>rand(100000,500000)]
                );
            }
        }
        News::create([
            'title' => 'Vé xe đón Tết sum vầy – Hành trình trở về nhà trọn vẹn',
            'slug' => 've-xe-don-tet-sum-vay',
            'thumbnail' => 'tintuc1.png',
            'excerpt' => 'Hành trình trở về nhà dịp Tết không chỉ là chuyến đi, mà là sự đoàn viên và yêu thương.',
            'content' => '
        <p>Tết Nguyên Đán là dịp lễ quan trọng nhất trong năm đối với mỗi người Việt Nam.</p>

        <p>Đặt vé xe về quê đón Tết không chỉ đơn thuần là mua một tấm vé, mà còn là sự chuẩn bị cho hành trình trở về nhà, nơi có gia đình đang mong ngóng.</p>

        <p>Việc đặt vé sớm giúp hành khách chủ động thời gian, tránh tình trạng cháy vé và đảm bảo có chỗ ngồi tốt nhất.</p>

        <h3>Lời khuyên khi đặt vé Tết</h3>
        <ul>
            <li>Đặt vé trước từ 2–4 tuần</li>
            <li>Chọn nhà xe uy tín</li>
            <li>Kiểm tra kỹ thông tin vé</li>
        </ul>
    ',
            'category' => 'Nổi bật',
            'is_featured' => true,
            'published_at' => now(),
        ]);
        News::create([
            'title' => 'Kinh nghiệm đặt vé xe dịp Tết tránh hết vé, tránh cò',
            'slug' => 'kinh-nghiem-dat-ve-xe-dip-tet',
            'thumbnail' => 'tintuc2.png',
            'excerpt' => 'Những kinh nghiệm thực tế giúp bạn đặt vé xe dịp Tết nhanh chóng, an toàn và đúng giá.',
            'content' => '
        <p>Dịp Tết là thời điểm nhu cầu đi lại tăng cao, khiến vé xe thường xuyên trong tình trạng khan hiếm.</p>

        <p>Để tránh tình trạng bị ép giá hoặc mua phải vé không hợp lệ, hành khách nên đặt vé qua các nền tảng uy tín.</p>

        <h3>Mẹo đặt vé xe Tết hiệu quả</h3>
        <ul>
            <li>Đặt vé càng sớm càng tốt</li>
            <li>Tránh mua vé qua trung gian không rõ nguồn gốc</li>
            <li>Ưu tiên đặt vé online</li>
        </ul>
    ',
            'category' => 'Kinh nghiệm',
            'is_featured' => false,
            'published_at' => now(),
        ]);
        News::create([
            'title' => 'Những tuyến xe đông khách nhất dịp Tết Nguyên Đán',
            'slug' => 'nhung-tuyen-xe-dong-khach-dip-tet',
            'thumbnail' => 'tintuc3.png',
            'excerpt' => 'Danh sách các tuyến xe thường xuyên cháy vé trong dịp Tết mà bạn cần lưu ý.',
            'content' => '
        <p>Mỗi dịp Tết đến, nhu cầu di chuyển tăng mạnh tại các tuyến xe liên tỉnh.</p>

        <p>Các tuyến từ TP.HCM, Hà Nội đi các tỉnh miền Trung và Tây Nguyên thường xuyên kín chỗ.</p>

        <h3>Các tuyến xe đông khách</h3>
        <ul>
            <li>TP.HCM – Quảng Ngãi</li>
            <li>TP.HCM – Nghệ An</li>
            <li>Hà Nội – Thanh Hóa</li>
        </ul>
    ',
            'category' => 'Tuyến xe',
            'is_featured' => false,
            'published_at' => now(),
        ]);
        News::create([
            'title' => 'Những lưu ý quan trọng khi đi xe đường dài ngày Tết',
            'slug' => 'luu-y-khi-di-xe-duong-dai-ngay-tet',
            'thumbnail' => 'tintuc4.png',
            'excerpt' => 'Những điều cần biết để chuyến đi đường dài dịp Tết an toàn và thoải mái.',
            'content' => '
        <p>Di chuyển đường dài trong dịp Tết đòi hỏi sự chuẩn bị kỹ lưỡng.</p>

        <p>Hành khách nên mang theo đồ dùng cá nhân, giữ sức khỏe và tuân thủ hướng dẫn của nhà xe.</p>

        <h3>Lưu ý quan trọng</h3>
        <ul>
            <li>Ăn uống nhẹ trước chuyến đi</li>
            <li>Giữ gìn tư trang cá nhân</li>
            <li>Không chen lấn khi lên xe</li>
        </ul>
    ',
            'category' => 'Lưu ý',
            'is_featured' => false,
            'published_at' => now(),
        ]);
        News::create([
            'title' => 'Vì sao nên đặt vé xe sớm trước Tết Nguyên Đán',
            'slug' => 'vi-sao-nen-dat-ve-xe-som-truoc-tet',
            'thumbnail' => 'tintuc5.png',
            'excerpt' => 'Đặt vé sớm giúp tiết kiệm chi phí và chủ động lịch trình trong dịp Tết.',
            'content' => '
        <p>Đặt vé xe sớm mang lại nhiều lợi ích cho hành khách trong dịp Tết.</p>

        <p>Bạn sẽ có nhiều lựa chọn về chỗ ngồi, khung giờ và giá vé hợp lý.</p>

        <h3>Lợi ích khi đặt vé sớm</h3>
        <ul>
            <li>Tránh tình trạng cháy vé</li>
            <li>Giá vé ổn định</li>
            <li>Chủ động kế hoạch di chuyển</li>
        </ul>
    ',
            'category' => 'Mẹo hay',
            'is_featured' => false,
            'published_at' => now(),
        ]);
    }
}
