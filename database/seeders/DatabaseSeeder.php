<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Bus;
use App\Models\City;

use App\Models\News;
use App\Models\Trip;
use App\Models\User;
use App\Models\Route;
use App\Models\PointFare;
use App\Models\PickupPoint;
use Illuminate\Support\Str;
use App\Models\DropoffPoint;
use Illuminate\Database\Seeder;
use App\Models\PickupDropoffPoint;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * ======================================================
         * 1. CITIES – Thành phố
         * ======================================================
         */
        $cityData = [
            ['name' => 'Hà Nội', 'code' => 'HN'],
            ['name' => 'TP. Hồ Chí Minh', 'code' => 'HCM'],
            ['name' => 'Đà Nẵng', 'code' => 'DN'],
            ['name' => 'Hải Phòng', 'code' => 'HP'],
            ['name' => 'Cần Thơ', 'code' => 'CT'],
            ['name' => 'Nha Trang', 'code' => 'NT'],
            ['name' => 'Huế', 'code' => 'HUE'],
            ['name' => 'Vinh', 'code' => 'VINH'],
            ['name' => 'Đà Lạt', 'code' => 'DL'],
        ];

        $cities = collect();
        foreach ($cityData as $item) {
            $cities[$item['code']] = City::updateOrCreate(
                ['code' => $item['code']],
                ['name' => $item['name'], 'status' => 1]
            );
        }

        /**
         * ======================================================
         * 2. USERS – Tài khoản hệ thống (CỐ ĐỊNH)
         * ======================================================
         */
        $systemUsers = [
            ['email' => 'admin@polycoach.test',   'role' => 'admin',   'name' => 'Super Admin'],
            ['email' => 'staff@polycoach.test',   'role' => 'staff',   'name' => 'Staff User'],
            ['email' => 'checker@polycoach.test', 'role' => 'checker', 'name' => 'Checker User'],
        ];

        foreach ($systemUsers as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'user_code' => 'PC-' . strtoupper($u['role']) . '-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                    'first_name' => explode(' ', $u['name'])[0],
                    'last_name'  => explode(' ', $u['name'])[1] ?? '',
                    'full_name'  => $u['name'],
                    'password'   => Hash::make('1'),
                    'role'       => $u['role'],
                    'status'     => 1,
                ]
            );
        }

        // User khách hàng (random)
        User::factory()->count(10)->state(['role' => 'user'])->create();

        /**
         * ======================================================
         * 3. ROUTES – Tuyến đường
         * ======================================================
         */
        $routeData = [
            ['from' => 'HN', 'to' => 'HCM', 'distance' => 1700, 'time' => '22:00:00'],
            ['from' => 'HCM', 'to' => 'HN', 'distance' => 1700, 'time' => '23:00:00'],
            ['from' => 'HN', 'to' => 'DN',  'distance' => 800,  'time' => '15:00:00'],
            ['from' => 'DN', 'to' => 'HCM', 'distance' => 960,  'time' => '18:00:00'],
            ['from' => 'HCM', 'to' => 'CT', 'distance' => 170,  'time' => '04:00:00'],
        ];

        $routes = collect();
        foreach ($routeData as $r) {
            $route = Route::updateOrCreate(
                [
                    'from_city_id' => $cities[$r['from']]->id,
                    'to_city_id'   => $cities[$r['to']]->id,
                ],
                [
                    'distance'       => $r['distance'],
                    'estimated_time' => $r['time'],
                    'status'         => 1,
                ]
            );
            $routes->push($route);
        }

        /**
         * ======================================================
         * 4. BUSES – Xe
         * ======================================================
         */
        $busData = [
            ['plate' => '29B-88888', 'type' => 'sleeper',    'seats' => 32],
            ['plate' => '51B-12345', 'type' => 'sleeper',    'seats' => 32],
            ['plate' => '43B-45678', 'type' => 'limousine', 'seats' => 32],
            ['plate' => '29A-34567', 'type' => 'seat',      'seats' => 32],
        ];

        $buses = collect();
        foreach ($busData as $b) {
            $buses->push(
                Bus::updateOrCreate(
                    ['plate_number' => $b['plate']],
                    ['type' => $b['type'], 'seat_count' => $b['seats'], 'status' => 1]
                )
            );
        }

        /**
         * ======================================================
         * 5. TRIPS – Chuyến xe (CÓ LOGIC THỜI GIAN)
         * ======================================================
         */
        foreach (range(1, 20) as $i) {
            $route = $routes->random();
            $bus   = $buses->random();

            // DateTime xuất bến
            $departureDateTime = Carbon::now()
                ->addDays(rand(1, 20))
                ->setTime(rand(5, 22), 0);

            // DateTime đến (sau 4–24h)
            $arrivalDateTime = (clone $departureDateTime)
                ->addHours(rand(4, 24));

            // Cao điểm Tết
            $isTet = $departureDateTime->between(
                Carbon::create($departureDateTime->year, 1, 25),
                Carbon::create($departureDateTime->year, 2, 15)
            );

            Trip::create([
                'route_id'        => $route->id,
                'bus_id'          => $bus->id,

                // Thời gian xuất bến
                'departure_date'  => $departureDateTime->toDateString(),
                'departure_time'  => $departureDateTime->format('H:i:s'),

                // Thời gian đến
                'arrival_date'    => $arrivalDateTime->toDateString(),
                'arrival_time'    => $arrivalDateTime->format('H:i:s'),

                // Giá vé
                'ticket_price'    => $isTet
                    ? rand(450000, 700000)
                    : rand(200000, 450000),

                // Trạng thái
                'status'          => 1, // hiển thị
                'trip_status'     => 1, // đã duyệt

                // Mã chuyến
                'trip_code'       => 'TRIP-' . strtoupper(Str::random(8)),

                // Duyệt chuyến
                'checked_at'      => now(),
                'checked_by'      => 1, // admin
            ]);
        }

        /**
         * ======================================================
         * 6. PICKUP / DROPOFF POINTS
         * ======================================================
         */

        //  foreach ($routes as $route) {
        //     $fromCity = $route->fromCity;
        //     $toCity   = $route->toCity;

        //     $points = [
        //         // Pickup
        //         [
        //             'route_id' => $route->id,
        //             'type'     => 'pickup',
        //             'name'     => 'Bến xe trung tâm ' . $fromCity->name,
        //             'address'  => 'Bến xe trung tâm ' . $fromCity->name,
        //             // 'order'    => 1,
        //             'active'   => 1,
        //         ],
        //         [
        //             'route_id' => $route->id,
        //             'type'     => 'pickup',
        //             'name'     => 'Văn phòng chính ' . $fromCity->name,
        //             'address'  => 'Văn phòng PoLyCoach tại ' . $fromCity->name,
        //             // 'order'    => 2,
        //             'active'   => 1,
        //         ],

        //         // Dropoff
        //         [
        //             'route_id' => $route->id,
        //             'type'     => 'dropoff',
        //             'name'     => 'Bến xe trung tâm ' . $toCity->name,
        //             'address'  => 'Bến xe trung tâm ' . $toCity->name,
        //             // 'order'    => 1,
        //             'active'   => 1,
        //         ],
        //         [
        //             'route_id' => $route->id,
        //             'type'     => 'dropoff',
        //             'name'     => 'Văn phòng chính ' . $toCity->name,
        //             'address'  => 'Văn phòng PoLyCoach tại ' . $toCity->name,
        //             // 'order'    => 2,
        //             'active'   => 1,
        //         ],
        //     ];

        //     PickupDropoffPoint::insert($points);
        // }
        // $cityPoints = [
        //     'HN' => [
        //         'Bến xe Mỹ Đình',
        //         'Bến xe Giáp Bát',
        //         'Bến xe Gia Lâm',
        //         'Văn phòng PoLyCoach Cầu Giấy',
        //         'Ngã tư Sở',
        //     ],
        //     'HCM' => [
        //         'Bến xe Miền Đông',
        //         'Bến xe Miền Tây',
        //         'Bến xe An Sương',
        //         'Văn phòng PoLyCoach Quận 1',
        //         'Ngã tư Hàng Xanh',
        //     ],
        //     'DN' => [
        //         'Bến xe Trung tâm Đà Nẵng',
        //         'Bến xe phía Nam Đà Nẵng',
        //         'Văn phòng PoLyCoach Hải Châu',
        //         'Cầu Rồng',
        //         'Ngã tư Ngô Quyền – Ngũ Hành Sơn',
        //     ],
        //     'HP' => [
        //         'Bến xe Niệm Nghĩa',
        //         'Bến xe Lạc Long',
        //         'Bến xe Thượng Lý',
        //         'Văn phòng PoLyCoach Lê Chân',
        //         'Ngã tư Quán Toan',
        //     ],
        //     'CT' => [
        //         'Bến xe Trung tâm Cần Thơ',
        //         'Bến xe Ô Môn',
        //         'Văn phòng PoLyCoach Ninh Kiều',
        //         'Ngã tư Trần Hưng Đạo – 30/4',
        //         'Siêu thị Big C Cần Thơ',
        //     ],
        //     'NT' => [
        //         'Bến xe phía Nam Nha Trang',
        //         'Bến xe phía Bắc Nha Trang',
        //         'Văn phòng PoLyCoach Trần Phú',
        //         'Ngã tư Mả Vòng',
        //         'Coopmart Nha Trang',
        //     ],
        //     'HUE' => [
        //         'Bến xe phía Bắc Huế',
        //         'Bến xe phía Nam Huế',
        //         'Văn phòng PoLyCoach Trần Hưng Đạo',
        //         'Ngã tư An Cựu',
        //         'Ga Huế',
        //     ],
        //     'VINH' => [
        //         'Bến xe Vinh',
        //         'Bến xe Bắc Vinh',
        //         'Văn phòng PoLyCoach Lê Lợi',
        //         'Ngã tư Quán Bàu',
        //         'Ga Vinh',
        //     ],
        //     'DL' => [
        //         'Bến xe Liên tỉnh Đà Lạt',
        //         'Bến xe Đức Trọng',
        //         'Văn phòng PoLyCoach trung tâm Đà Lạt',
        //         'Chợ Đà Lạt',
        //         'Ngã ba Prenn',
        //     ],
        // ];

        // // Lấy tất cả các route
        // $routes = Route::with(['fromCity', 'toCity'])->get();

        // foreach ($routes as $route) {

        //     // 1. Chuẩn hoá giờ chạy
        //     $departureTime = $route->departure_time ?? '08:00';
        //     $duration      = $route->estimated_duration ?? 600; // phút

        //     $departure = Carbon::createFromTimeString($departureTime);

        //     $fromCityCode = $route->fromCity->code;
        //     $toCityCode   = $route->toCity->code;

        //     $pickupNames  = $cityPoints[$fromCityCode] ?? [];
        //     $dropoffNames = $cityPoints[$toCityCode] ?? [];

        //     // 2. Pickup points (trước giờ chạy)
        //     foreach ($pickupNames as $i => $name) {
        //         PickupDropoffPoint::create([
        //             'city_id'  => $route->from_city_id,
        //             'route_id' => $route->id,
        //             'type'     => 'pickup',
        //             'name'     => $name,
        //             'address'  => $name,
        //             'time'     => $departure
        //                 ->copy()
        //                 ->subMinutes(60 - ($i * 15))
        //                 ->format('H:i'),
        //             'active'   => 1,
        //         ]);
        //     }

        //     // 3. Dropoff points (sau khi xe đến)
        //     foreach ($dropoffNames as $i => $name) {
        //         PickupDropoffPoint::create([
        //             'city_id'  => $route->to_city_id,
        //             'route_id' => $route->id,
        //             'type'     => 'dropoff',
        //             'name'     => $name,
        //             'address'  => $name,
        //             'time'     => $departure
        //                 ->copy()
        //                 ->addMinutes($duration + ($i * 10))
        //                 ->format('H:i'),
        //             'active'   => 1,
        //         ]);
        //     }
        // }
        // /**
        //  * ======================================================
        //  * 7. POINT FARES – Giá vé giữa các điểm
        //  * ======================================================
        //  */
        // foreach ($routes as $route) {

        //     $pickups = PickupDropoffPoint::where('route_id', $route->id)
        //         ->where('type', 'pickup')
        //         ->get();

        //     $dropoffs = PickupDropoffPoint::where('route_id', $route->id)
        //         ->where('type', 'dropoff')
        //         ->get();

        //     foreach ($pickups as $pickup) {
        //         foreach ($dropoffs as $dropoff) {

        //             PointFare::updateOrCreate(
        //                 [
        //                     'route_id'         => $route->id,
        //                     'pickup_point_id'  => $pickup->id,
        //                     'dropoff_point_id' => $dropoff->id,
        //                 ],
        //                 [
        //                     'price'  => rand(200000, 600000),
        //                     'active' => 1,
        //                 ]
        //             );
        //         }
        //     }
        // }
        /**
         * ======================================================
         * 8. NEWS – Tin tức
         * ======================================================
         */
        $adminId = User::where('role', 'admin')->first()->id;

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
            'status' => 'published',
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
            'status' => 'published',
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
            'status' => 'published',
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
            'status' => 'published',
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
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
    // private function pickupTime($departureTime, $index)
    // {
    //     $departureTime = $departureTime ?? '08:00';

    //     return Carbon::createFromTimeString($departureTime)
    //         ->subMinutes(60 - ($index * 15))
    //         ->format('H:i');
    // }

    // private function dropoffTime($departureTime, $duration, $index)
    // {
    //     $departureTime = $departureTime ?? '08:00';
    //     $duration = $duration ?? 600; // 10 tiếng mặc định

    //     return Carbon::createFromTimeString($departureTime)
    //         ->addMinutes($duration + ($index * 10))
    //         ->format('H:i');
    // }
}
