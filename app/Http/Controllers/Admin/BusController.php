<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $buses = Bus::latest()->paginate(25);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.buses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'bien_so' => 'required|max:20|unique:buses,bien_so',
            'so_ghe' => 'required|integer|min:4|max:100',
            'loai_xe' => 'required|in:Giường,Limousine',
        ]);

        Bus::create($request->all());
        return redirect()->route('admin.buses.index')->with('success', 'Thêm xe mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bus $bus)
    {
        //
        return view('admin.buses.show', compact('buses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bus $bus)
    {
        //
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bus $bus)
{
    $request->validate([
        'bien_so' => 'required|max:20|unique:buses,bien_so,' . $bus->id,
        'so_ghe' => 'required|integer|min:4',
        'loai_xe' => 'required|in:Giường nằm,Limousine',
    ]);

    $bus->update([
        'bien_so' => $request->bien_so,
        'so_ghe' => $request->so_ghe,
        'loai_xe' => $request->loai_xe,
        'trang_thai' => $request->trang_thai ?? $bus->trang_thai,
    ]);

    return redirect()->route('admin.buses.index')
                     ->with('success', 'Cập nhật thông tin xe thành công!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bus $bus)
    {
        //
    }
}
