<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RouteController extends Controller
{
    /**
     * Display a listing of routes.
     */
    public function index(Request $request)
    {
        $query = Route::with(['fromCity', 'toCity']);

        if ($search = $request->string('search')->trim()) {
            $query->whereHas('fromCity', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
                ->orWhereHas('toCity', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', (int) $request->status);
        }

        $routes = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new route.
     */
    public function create()
    {
        $cities = City::orderBy('name')->get();

        return view('admin.routes.create', compact('cities'));
    }

    /**
     * Store a newly created route in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'from_city_id' => ['required', 'exists:cities,id', 'different:to_city_id'],
                'to_city_id' => ['required', 'exists:cities,id'],
                'distance' => ['required', 'numeric', 'min:1'],
                'estimated_time' => ['required', 'string', 'max:50'],
                'status' => ['nullable', 'boolean'],
            ],
            [
                'from_city_id.required' => 'Vui lòng chọn thành phố đi.',
                'from_city_id.exists' => 'Thành phố đi không tồn tại.',
                'to_city_id.required' => 'Vui lòng chọn thành phố đến.',
                'to_city_id.exists' => 'Thành phố đến không tồn tại.',
                'distance.required' => 'Vui lòng nhập khoảng cách.',
                'distance.numeric' => 'Khoảng cách phải là một số.',
                'distance.min' => 'Khoảng cách phải lớn hơn 0.',
                'estimated_time.required' => 'Vui lòng nhập thời gian ước tính.',
                'from_city_id.different' => 'Thành phố đi và thành phố đến phải khác nhau.',

            ]
        );

        $data['status'] = $request->boolean('status');

        //kiểm tra trùng lặp
        $existingRoute = Route::where('from_city_id', $data['from_city_id'])
            ->where('to_city_id', $data['to_city_id'])
            ->first();
        if ($existingRoute) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['route_exists' => 'Tuyến đường này đã tồn tại.']);
        }
        Route::create($data);

        return redirect()
            ->route('admin.routes.index')
            ->with('success', 'Thêm tuyến đường thành công');
    }


    /**
     * Show the form for editing the specified route.
     */
    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified route in storage.
     */
    public function update(Request $request, Route $route): RedirectResponse
    {
        $data = $request->validate([
            'from_city_id' => 'required|integer|exists:cities,id|different:to_city_id',
            'to_city_id' => 'required|integer|exists:cities,id',
            'distance' => 'required|integer|min:1',
            'estimated_time' => 'required|string|max:50',
            'status' => 'required|in:0,1',
        ], [
            'from_city_id.required' => 'Vui lòng chọn thành phố đi.',
            'from_city_id.exists' => 'Thành phố đi không tồn tại.',
            'to_city_id.required' => 'Vui lòng chọn thành phố đến.',
            'to_city_id.exists' => 'Thành phố đến không tồn tại.',
            'distance.required' => 'Vui lòng nhập khoảng cách.',
            'distance.numeric' => 'Khoảng cách phải là một số.',
            'distance.min' => 'Khoảng cách phải lớn hơn 0.',
            'estimated_time.required' => 'Vui lòng nhập thời gian ước tính.',
            'from_city_id.different' => 'Thành phố đi và thành phố đến phải khác nhau.',
        ]);
        // kiểm tra trùng lặp
        $existingRoute = Route::where('from_city_id', $data['from_city_id'])
            ->where('to_city_id', $data['to_city_id'])
            ->where('id', '!=', $route->id)
            ->first();
        if ($existingRoute) {
            return redirect()->back()->withInput()
                ->withErrors(['route_exists' => 'Tuyến đường này đã tồn tại.']);
        }

        $route->update($data);

        return redirect()->route('admin.routes.index')->with('success', 'Route updated successfully!');
    }

    /**
     * Display the specified route.
     */
    public function show(Route $route)
    {
        return view('admin.routes.show', compact('route'));
    }

    /**
     * Remove the specified route from storage.
     */
    public function destroy(Route $route): RedirectResponse
    {
        if ($route->trips()->count() > 0) {
            return redirect()->route('admin.routes.index')
                ->withErrors('Cannot delete this route because there are trips using it.');
        }

        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Route deleted successfully!');
    }

    /**
     * Display soft-deleted routes (trash).
     */
    public function trash()
    {
        $routes = Route::onlyTrashed()->paginate(25);
        return view('admin.routes.trash', compact('routes'));
    }

    /**
     * Restore a soft-deleted route.
     */
    public function restore($id): RedirectResponse
    {
        $route = Route::onlyTrashed()->findOrFail($id);
        $route->restore();
        return redirect()->route('admin.routes.index')->with('success', 'Route restored successfully!');
    }

    /**
     * Permanently delete a route.
     */
    public function forceDelete($id): RedirectResponse
    {
        $route = Route::onlyTrashed()->findOrFail($id);

        if ($route->trips()->count() > 0) {
            return redirect()->route('admin.routes.trash')
                ->withErrors('Cannot permanently delete this route because there are trips using it.');
        }

        $route->forceDelete();
        return redirect()->route('admin.routes.trash')->with('success', 'Route permanently deleted!');
    }
}
