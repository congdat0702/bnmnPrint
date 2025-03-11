<?php

namespace App\Http\Controllers;
use App\Models\Sender;

use Illuminate\Http\Request;

class SenderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $sender = Sender::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'address' => $request->address,
        ]);

        return response()->json($sender, 201);
    }

    // Lấy danh sách Sender
    public function index()
    {
        $senders = Sender::all();
        return response()->json($senders);
    }
}
