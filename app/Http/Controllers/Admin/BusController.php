<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Hiển thị danh sách xe
     */
    public function index()
    {
        $buses = Bus::latest()->paginate(25);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Hiển thị form thêm xe mới
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Lưu xe mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'bien_so'   => 'required|string|max:20|unique:buses,bien_so',
            'so_ghe'    => 'required|integer|min:4|max:100',
            'loai_xe'   => 'required|in:Ghế ngồi,Giường nằm,Limousine',
            'trang_thai' => 'nullable|in:0,1',
        ]);

        Bus::create($request->only(['bien_so', 'so_ghe', 'loai_xe', 'trang_thai']));

        return redirect()->route('admin.buses.index')->with('success', 'Thêm xe mới thành công!');
    }

    /**
     * Xem chi tiết xe
     */
    public function show(Bus $bus)
    {
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Cập nhật thông tin xe
     */
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'bien_so'   => 'required|string|max:20|unique:buses,bien_so,' . $bus->id,
            'so_ghe'    => 'required|integer|min:4|max:100',
            'loai_xe'   => 'required|in:Ghế ngồi,Giường nằm,Limousine',
            'trang_thai'=> 'nullable|in:0,1',
        ]);

        $bus->update($request->only(['bien_so', 'so_ghe', 'loai_xe', 'trang_thai']));

        return redirect()->route('admin.buses.index')->with('success', 'Cập nhật thông tin xe thành công!');
    }

    /**
     * Xóa (mềm)
     */
    public function destroy(Bus $bus)
    {
    }
}
