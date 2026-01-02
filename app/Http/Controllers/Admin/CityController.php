<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    /**
     * Danh sách thành phố (có tìm kiếm + phân trang)
     */
    public function index(Request $request)
    {
        $query = City::query();

        // Tìm theo tên hoặc mã
        if ($search = $request->string('search')->trim()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        // Lọc trạng thái: 1 = active, 0 = inactive
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', (int) $request->input('status'));
        }

        // Nếu muốn xem thùng rác (tùy ông có xài hay không)
        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        }

        $cities = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // API JSON (nếu có dùng)
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $cities->items(),
                'meta' => [
                    'current_page' => $cities->currentPage(),
                    'last_page' => $cities->lastPage(),
                    'per_page' => $cities->perPage(),
                    'total' => $cities->total(),
                ],
            ]);
        }

        // View Blade Bootstrap
        return view('admin.cities.index', compact('cities'));
    }

    /**
     * Form tạo mới thành phố
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    /**
     * Lưu thành phố mới
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('cities', 'name'),
            ],
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('cities', 'code'),
            ],
            'status' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Tên thành phố là bắt buộc.',
            'name.unique' => 'Tên thành phố đã tồn tại.',
            'code.unique' => 'Mã thành phố đã tồn tại.',
        ]);

        // checkbox: nếu không tick thì không gửi, dùng boolean() cho chắc
        $data['status'] = $request->boolean('status');

        $city = City::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tạo thành phố thành công',
                'data' => $city,
            ], 201);
        }

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Tạo thành phố thành công');
    }

    /**
     * Xem chi tiết 1 thành phố (ít dùng, tuỳ ông)
     */
    // public function show(City $city, Request $request)
    // {
    //     if ($request->wantsJson()) {
    //         return response()->json(['data' => $city]);
    //     }

    //     return view('admin.cities.show', compact('city'));
    // }

    /**
     * Form chỉnh sửa thành phố
     */
    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    /**
     * Cập nhật thành phố
     */
    public function update(Request $request, City $city)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('cities', 'name')->ignore($city->id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('cities', 'code')->ignore($city->id),
            ],
            'status' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Tên thành phố là bắt buộc.',
            'name.unique' => 'Tên thành phố đã tồn tại.',
            'code.unique' => 'Mã thành phố đã tồn tại.',
        ]);

        $data['status'] = $request->boolean('status');

        $city->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Cập nhật thành phố thành công',
                'data' => $city,
            ]);
        }

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Cập nhật thành phố thành công');
    }

    /**
     * Xoá mềm thành phố
     */
    public function destroy(Request $request, City $city)
    {
        $city->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Xoá thành phố thành công (đã đưa vào thùng rác)',
            ]);
        }

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Xoá thành phố thành công');
    }

    /**
     * Danh sách trong thùng rác
     */
    public function trash(Request $request)
    {
        $cities = City::onlyTrashed()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json(['data' => $cities->items()]);
        }

        return view('admin.cities.trash', compact('cities'));
    }

    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->restore();

        return redirect()
            ->route('admin.cities.trash')
            ->with('success', 'Khôi phục thành phố thành công');
    }

    /**
     * Xoá vĩnh viễn
     */
    public function forceDelete($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->forceDelete();

        return redirect()
            ->route('admin.cities.trash')
            ->with('success', 'Đã xoá vĩnh viễn thành phố');
    }
}

