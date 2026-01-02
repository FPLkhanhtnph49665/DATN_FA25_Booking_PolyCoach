<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách user.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('phone', 'like', "%$keyword%")
                    ->orWhere('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form thêm user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu user mới vào CSDL.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Email phải là duy nhất trong bảng users
            'email' => 'required|email|unique:users,email',
            // Phone phải duy nhất, đúng 10 số (size:10)
            'phone' => 'required|string|size:10|unique:users,phone|regex:/^[0-9]+$/',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|in:admin,user,staff,checker',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Bạn có thể thêm message tiếng Việt tại đây nếu muốn
            'first_name.required' => 'Họ là bắt buộc.',
            'last_name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.size' => 'Số điện thoại phải chính xác 10 ký tự.',
            'phone.unique' => 'Số điện thoại này đã tồn tại.',
            'email.unique' => 'Địa chỉ email này đã tồn tại.',
            'phone.regex' => 'Số điện thoại chỉ được chứa các chữ số.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        // Xử lý Upload ảnh vào public/uploads/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Di chuyển file trực tiếp vào thư mục public
            $file->move(public_path('uploads/user_images'), $filename);

            // Lưu đường dẫn vào database để hiển thị
            $data['image'] = 'uploads/user_images/' . $filename;
        }

        // Mã hóa mật khẩu
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết user.
     */
    // public function show(User $user)
    // {
    //     return view('admin.users.show', compact('user'));
    // }

    /**
     * Hiển thị form sửa user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            // Email duy nhất, ngoại trừ bản ghi hiện tại
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Phone: đúng 10 số, duy nhất (trừ bản thân), chỉ chứa số
            'phone' => 'required|string|size:10|regex:/^[0-9]+$/|unique:users,phone,' . $user->id,
            'role' => 'required|in:admin,user,staff,checker',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'first_name.required' => 'Họ là bắt buộc.',
            'last_name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.size' => 'Số điện thoại phải chính xác 10 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'phone.regex' => 'Số điện thoại chỉ được chứa các chữ số.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',

        ]);

        $data = $request->only(['first_name', 'last_name', 'email', 'phone', 'role', 'status']);

        // Cập nhật full name
        $data['full_name'] = $request->first_name . ' ' . $request->last_name;

        // Nếu có nhập mật khẩu mới
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Xử lý upload ảnh mới
        if ($request->hasFile('image')) {

            // 1. Xóa ảnh cũ trong thư mục public nếu tồn tại
            if ($user->image) {
                $oldImagePath = public_path($user->image); // Lấy đường dẫn tuyệt đối
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath); // Xóa file vật lý
                }
            }

            // 2. Lưu ảnh mới vào public/uploads/user_images
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/user_images'), $filename);

            // 3. Cập nhật đường dẫn mới vào mảng dữ liệu
            $data['image'] = 'uploads/user_images/' . $filename;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công!');
    }


    /**
     * Xóa user.
     */
    public function destroy(User $user)
    {
        // Nếu muốn kiểm tra trước khi xóa (ví dụ: user có liên kết ticket, payment)
        if ($user->tickets()->count() > 0 || $user->payments()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->withErrors('Không thể xóa user vì đã có dữ liệu liên quan!');
        }

        $user->delete(); // Soft delete
        return redirect()->route('admin.users.index')
            ->with('success', 'User đã được xóa thành công!');
    }
}
