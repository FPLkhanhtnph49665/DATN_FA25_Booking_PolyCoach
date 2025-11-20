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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================
        // 0. Seed Cities (thành phố cố định)
        // =====================================
        $cityData = [
            ['name' => 'Hà Nội',          'code' => 'HN',   'status' => 1],
            ['name' => 'TP. Hồ Chí Minh', 'code' => 'HCM',  'status' => 1],
            ['name' => 'Đà Nẵng',         'code' => 'DN',   'status' => 1],
            ['name' => 'Hải Phòng',       'code' => 'HP',   'status' => 1],
            ['name' => 'Cần Thơ',         'code' => 'CT',   'status' => 1],
            ['name' => 'Nha Trang',       'code' => 'NT',   'status' => 1],
            ['name' => 'Huế',             'code' => 'HUE',  'status' => 1],
            ['name' => 'Vinh',            'code' => 'VINH', 'status' => 1],
            ['name' => 'Buôn Ma Thuột',   'code' => 'BMT',  'status' => 1],
            ['name' => 'Đà Lạt',          'code' => 'DL',   'status' => 1],
        ];

        $cities = collect();

        foreach ($cityData as $data) {
            $cities[$data['code']] = City::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name'   => $data['name'],
                    'status' => $data['status'],
                ]
            );
        }

        // =====================================
        // 1. Seed Users (admin + user + factory)
        // =====================================

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
        $routeData = [
            // Hà Nội <-> TP. HCM
            [
                'from_code'      => 'HN',
                'to_code'        => 'HCM',
                'distance'       => 1700,
                'estimated_time' => '22:00:00', // 30 giờ
                'status'         => 1,
            ],
            [
                'from_code'      => 'HCM',
                'to_code'        => 'HN',
                'distance'       => 1700,
                'estimated_time' => '23:00:00',
                'status'         => 1,
            ],

            // Hà Nội -> Đà Nẵng
            [
                'from_code'      => 'HN',
                'to_code'        => 'DN',
                'distance'       => 800,
                'estimated_time' => '15:00:00',
                'status'         => 1,
            ],

            // Đà Nẵng -> TP. HCM
            [
                'from_code'      => 'DN',
                'to_code'        => 'HCM',
                'distance'       => 960,
                'estimated_time' => '18:00:00',
                'status'         => 1,
            ],

            // TP. HCM -> Cần Thơ
            [
                'from_code'      => 'HCM',
                'to_code'        => 'CT',
                'distance'       => 170,
                'estimated_time' => '04:00:00',
                'status'         => 1,
            ],

            // TP. HCM -> Nha Trang
            [
                'from_code'      => 'HCM',
                'to_code'        => 'NT',
                'distance'       => 430,
                'estimated_time' => '09:00:00',
                'status'         => 1,
            ],

            // TP. HCM -> Đà Lạt
            [
                'from_code'      => 'HCM',
                'to_code'        => 'DL',
                'distance'       => 300,
                'estimated_time' => '08:00:00',
                'status'         => 1,
            ],

            // Hà Nội -> Hải Phòng
            [
                'from_code'      => 'HN',
                'to_code'        => 'HP',
                'distance'       => 120,
                'estimated_time' => '03:00:00',
                'status'         => 1,
            ],

            // Hà Nội -> Vinh
            [
                'from_code'      => 'HN',
                'to_code'        => 'VINH',
                'distance'       => 300,
                'estimated_time' => '06:00:00',
                'status'         => 1,
            ],

            // Huế -> Đà Nẵng
            [
                'from_code'      => 'HUE',
                'to_code'        => 'DN',
                'distance'       => 100,
                'estimated_time' => '02:30:00',
                'status'         => 1,
            ],
        ];

        $routes = collect();

        foreach ($routeData as $data) {
            $fromCity = $cities[$data['from_code']] ?? null;
            $toCity   = $cities[$data['to_code']] ?? null;

            if (!$fromCity || !$toCity) {
                continue;
            }

            $route = Route::updateOrCreate(
                [
                    'from_city_id' => $fromCity->id,
                    'to_city_id'   => $toCity->id,
                ],
                [
                    'distance'       => $data['distance'],
                    'estimated_time' => $data['estimated_time'],
                    'status'         => $data['status'],
                ]
            );

            $routes->push($route);
        }

        // Load lại routes kèm quan hệ city cho chắc
        $routes = Route::with(['fromCity', 'toCity'])->get();

        // =====================================
        // 3. Seed Buses (xe cụ thể)
        // =====================================
        $busData = [
            [
                'plate_number' => '29B-88888',
                'seat_count'   => 32,
                'type'         => 'Giường nằm',
                'status'       => 1,
            ],
            [
                'plate_number' => '51B-12345',
                'seat_count'   => 32,
                'type'         => 'Giường nằm cao cấp',
                'status'       => 1,
            ],
            [
                'plate_number' => '43B-54953',
                'seat_count'   => 32,
                'type'         => 'Limousine',
                'status'       => 1,
            ],
            [
                'plate_number' => '29A-34953',
                'seat_count'   => 32,
                'type'         => 'Ghế ngồi',
                'status'       => 1,
            ],
            [
                'plate_number' => '29A-44953',
                'seat_count'   => 32,
                'type'         => 'Giường nằm',
                'status'       => 0, // đang bảo trì
            ],
        ];

        $buses = collect();

        foreach ($busData as $data) {
            $buses->push(
                Bus::updateOrCreate(
                    ['plate_number' => $data['plate_number']],
                    [
                        'seat_count' => $data['seat_count'],
                        'type'       => $data['type'],
                        'status'     => $data['status'],
                    ]
                )
            );
        }

        // =====================================
        // 4. Seed Trips (dùng factory, gắn route + bus)
        // =====================================
        if ($routes->isNotEmpty() && $buses->isNotEmpty()) {
            Trip::factory()
                ->count(20)
                ->state(function () use ($routes, $buses) {
                    return [
                        'route_id' => $routes->random()->id,
                        'bus_id'   => $buses->random()->id,
                    ];
                })
                ->create();
        }

        // =====================================
        // 5. Seed Pickup / Dropoff Points
        // =====================================
        if ($routes->isNotEmpty()) {
            foreach ($routes as $route) {
                $fromCity = $route->fromCity;
                $toCity   = $route->toCity;

                if (!$fromCity || !$toCity) {
                    continue;
                }

                // Điểm đón tại thành phố đi
                PickupDropoffPoint::updateOrCreate(
                    [
                        'city_id'  => $fromCity->id,
                        'route_id' => $route->id,
                        'name'     => 'Bến xe ' . $fromCity->name,
                        'type'     => 'pickup',
                    ],
                    [
                        'address' => 'Bến xe trung tâm ' . $fromCity->name,
                        'time'    => '06:00',
                        'active'  => 1,
                    ]
                );

                // Điểm đón phụ tại thành phố đi
                PickupDropoffPoint::updateOrCreate(
                    [
                        'city_id'  => $fromCity->id,
                        'route_id' => $route->id,
                        'name'     => 'Văn phòng ' . $fromCity->name,
                        'type'     => 'pickup',
                    ],
                    [
                        'address' => 'Văn phòng PoLyCoach tại ' . $fromCity->name,
                        'time'    => '06:30',
                        'active'  => 1,
                    ]
                );

                // Điểm trả tại thành phố đến
                PickupDropoffPoint::updateOrCreate(
                    [
                        'city_id'  => $toCity->id,
                        'route_id' => $route->id,
                        'name'     => 'Bến xe ' . $toCity->name,
                        'type'     => 'dropoff',
                    ],
                    [
                        'address' => 'Bến xe trung tâm ' . $toCity->name,
                        'time'    => '20:00',
                        'active'  => 1,
                    ]
                );

                // Điểm trả phụ tại thành phố đến
                PickupDropoffPoint::updateOrCreate(
                    [
                        'city_id'  => $toCity->id,
                        'route_id' => $route->id,
                        'name'     => 'Văn phòng ' . $toCity->name,
                        'type'     => 'dropoff',
                    ],
                    [
                        'address' => 'Văn phòng PoLyCoach tại ' . $toCity->name,
                        'time'    => '20:30',
                        'active'  => 1,
                    ]
                );
            }
        }
    }
}
