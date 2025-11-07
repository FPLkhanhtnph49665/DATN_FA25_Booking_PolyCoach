<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Hiển thị danh sách tuyến.
     */
    public function index()
    {
        $routes = Route::latest()->paginate(25);
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Form tạo mới tuyến.
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Lưu tuyến mới vào database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'diem_di' => 'required|string|max:100',
            'diem_den' => 'required|string|max:100',
            'quang_duong' => 'required|integer|min:1',
            'thoi_gian_du_kien' => 'required|string|max:50',
            'trang_thai' => 'required|in:0,1',
        ]);

        Route::create($data);

        return redirect()->route('admin.routes.index')->with('success', 'Đã tạo tuyến thành công!');
    }

    /**
     * Hiển thị chi tiết tuyến (nếu cần).
     */
    public function show(Route $route)
    {
        return view('admin.routes.show', compact('route'));
    }

    /**
     * Form chỉnh sửa tuyến.
     */
    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Cập nhật tuyến.
     */
    public function update(Request $request, Route $route)
    {
        $data = $request->validate([
            'diem_di' => 'required|string|max:100',
            'diem_den' => 'required|string|max:100',
            'quang_duong' => 'required|integer|min:1',
            'thoi_gian_du_kien' => 'required|string|max:50',
            'trang_thai' => 'required|in:0,1',
        ]);

        $route->update($data);

        return redirect()->route('admin.routes.index')->with('success', 'Cập nhật tuyến thành công!');
    }

    /**
     * Xóa tuyến.
     */
    public function destroy(Route $route)
    {
    }
}
