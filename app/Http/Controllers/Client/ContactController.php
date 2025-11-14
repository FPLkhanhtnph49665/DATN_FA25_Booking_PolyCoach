<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Hiển thị form liên hệ
    public function showForm()
    {
        return view('client.contact');
    }

    // Xử lý gửi form
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Lưu vào bảng contacts (cần đúng tên cột trong migration của bạn)
        Contact::create($data);

        return redirect()
            ->route('client.contact.show')
            ->with('success', 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.');
    }
}
