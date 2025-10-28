<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sắp ra mắt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .countdown {
            font-size: 2rem;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div>
        <h1>DATN_FA25_Booking_PolyCoach đang được xây dựng</h1>
        <p>Hệ thống đặt vé và quản lý chuyến xe sẽ sớm ra mắt. Hãy đợi nhé!</p>

        <div id="countdown" class="countdown"></div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-lg">
            Vào trang Admin
        </a>
    </div>


    <script>
        // Set thời gian ra mắt (yyyy-mm-dd hh:mm:ss)
        const launchDate = new Date("2025-12-01 00:00:00").getTime();

        const countdownEl = document.getElementById("countdown");

        const x = setInterval(function () {
            const now = new Date().getTime();
            const distance = launchDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownEl.innerHTML = `${days} ngày ${hours} giờ ${minutes} phút ${seconds} giây`;

            if (distance < 0) {
                clearInterval(x);
                countdownEl.innerHTML = "Đã ra mắt!";
            }
        }, 1000);
    </script>
</body>

</html>
