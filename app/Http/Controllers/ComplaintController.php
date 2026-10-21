<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'    => ['required', Rule::in(array_keys(Complaint::TYPES))],
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'type.required'    => 'Please select a complaint type.',
            'message.required' => 'Please describe your complaint.',
        ]);

        $request->user()->complaints()->create($data + [
            'room_number' => $request->user()->room_number,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Complaint sent. We will get back to you soon.',
        ]);
    }
}