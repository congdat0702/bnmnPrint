<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PrintHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PrintHistoryController extends Controller
{
    // Method để lấy tất cả lịch sử in với phân trang
    public function index(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login'); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    }
    $perPage = $request->get('per_page', 30);
    // Sắp xếp theo thời gian in từ mới nhất đến cũ nhất
    $printHistories = PrintHistory::orderBy('printed_at', 'desc')->paginate($perPage)->through(function ($history) {
        return [
            'id' => $history->id,
            'name' => $history->recipient_name,
            'phone' => $history->recipient_phone,
            'printer' => $history->printed_by,
            'time' => $history->printed_at ? \Carbon\Carbon::parse($history->printed_at)->toDateTimeString() : null,
        ];
    });

    return Inertia::render('PrintHistory', [
        'auth' => Auth::user(),
        'printHistories' => $printHistories
    ]);

}
    
    // Method để lưu một lịch sử in mới
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        PrintHistory::create([
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'printed_by' => $user ? $user->name : 'Guest',
            'printed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}