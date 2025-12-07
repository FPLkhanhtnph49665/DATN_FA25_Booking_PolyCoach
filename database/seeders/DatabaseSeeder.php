<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use App\Models\Route;
use App\Models\Bus;
use App\Models\Trip;
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
    }
}
