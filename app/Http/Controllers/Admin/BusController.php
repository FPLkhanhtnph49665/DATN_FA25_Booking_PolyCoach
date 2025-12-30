<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use App\Models\BusImage;
class BusController extends Controller
{
    /**
     * Display a listing of buses.
     */
    public function index(Request $request)
    {
        // 1. Khởi tạo query kèm theo quan hệ 'images'
        $query = Bus::with('images');

        // 2. Lọc theo từ khóa tìm kiếm (Biển số hoặc Loại xe)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%");
            });
        }

        // 3. Lọc theo trạng thái
        // Lưu ý: Trong Blade bạn dùng status 1 (hoạt động) và 0 (bảo dưỡng)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 4. Thực hiện phân trang và giữ lại các tham số lọc trên URL (appends)
        $buses = $query->latest()->paginate(25)->withQueryString();

        return view('admin.buses.index', compact('buses'));
    }
    /**
     * Show the form for creating a new bus.
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Store a newly created bus in storage.
     */
    public function store(Request $request)
    {
        // 1. Cập nhật Validation: Thêm rule cho field 'image'
        $data = $request->validate([
            'plate_number' => 'required|string|max:20|unique:buses,plate_number',
            'seat_count' => 'required|integer|min:16|max:50',
            'type' => 'required|in:Seat,Sleeper,Limousine',
            'status' => 'nullable|in:0,1',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Bắt buộc up 1 ảnh, tối đa 2MB
        ]);

        // Chuẩn hóa type về lowercase
        $data['type'] = strtolower($data['type']);

        // Nếu không gửi status thì mặc định là active (1)
        $data['status'] = isset($data['status']) ? (int) $data['status'] : 1;

        // 2. Tạo xe
        $bus = Bus::create($data);

        // 3. Xử lý lưu ảnh vào thư mục public/uploads/bus_images/
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Tạo tên file: biển-số-xe_timestamp.extension (Ví dụ: 29B12345_1715000.jpg)
            $fileName = str_replace([' ', '.', '-'], '', $bus->plate_number) . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Di chuyển file vào thư mục public
            $file->move(public_path('uploads/bus_images'), $fileName);

            // Lưu bản ghi vào bảng bus_images qua quan hệ HasMany
            $bus->images()->create([
                'image_path' => 'uploads/bus_images/' . $fileName
            ]);
        }

        // 🔥 Sinh ghế mặc định
        $bus->generateDefaultSeats();

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Tạo xe, sơ đồ ghế và tải lên ảnh thành công!');
    }


    /**
     * Display the specified bus.
     */
    public function show(Bus $bus)
    {
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified bus.
     */
    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update the specified bus in storage.
     */
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20|unique:buses,plate_number,' . $bus->id,
            'seat_count' => 'required|integer|min:4|max:100',
            'type' => 'required|in:Seat,Sleeper,Limousine',
            'status' => 'nullable|in:0,1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Lấy dữ liệu và chuẩn hóa type về chữ thường giống như hàm store
        $data = $request->only(['plate_number', 'seat_count', 'type', 'status']);
        $data['type'] = strtolower($data['type']);
        $data['status'] = isset($data['status']) ? (int) $data['status'] : 1;

        // Cập nhật thông tin xe
        $bus->update($data);

        // Xử lý tải thêm nhiều ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $safePlate = str_replace([' ', '-', '.'], '', $bus->plate_number);
                $fileName = $safePlate . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/bus_images'), $fileName);

                $bus->images()->create([
                    'image_path' => 'uploads/bus_images/' . $fileName
                ]);
            }
        }

        return redirect()->route('admin.buses.index')->with('success', 'Cập nhật xe thành công!');
    }
    // Xóa ảnh xe
    public function destroyImage($id)
    {
        $image = BusImage::findOrFail($id);
        if (File::exists(public_path($image->image_path))) {
            File::delete(public_path($image->image_path));
        }
        $image->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified bus from storage.
     */
    public function destroy(Bus $bus)
    {
        // Check if bus is assigned to any trips
        if ($bus->trips()->count() > 0) {
            return redirect()->route('admin.buses.index')
                ->withErrors('Cannot delete this bus because it is assigned to trips!');
        }

        // Hard delete
        $bus->forceDelete();

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus deleted successfully!');
    }
}
