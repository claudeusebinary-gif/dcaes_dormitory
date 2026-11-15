<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AdminNotification;

class ComplaintController extends Controller
{
    public function store(Request $request): JsonResponse
{
    $data = $request->validate([
        'type'    => ['required', Rule::in(array_keys(Complaint::TYPES))],
        'message' => ['required', 'string', 'max:1000'],
        'photo'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ], [
        'type.required'    => 'Please select a complaint type.',
        'message.required' => 'Please describe your complaint.',
        'photo.mimes'      => 'The photo must be a JPG, PNG or WEBP image.',
        'photo.max'        => 'The photo must be 5 MB or smaller.',
        'photo.uploaded'   => 'The photo could not be uploaded. Try a smaller image.',
    ]);

    $photoPath = $request->hasFile('photo')
        ? $request->file('photo')->store('complaints', 'local')
        : null;

    $request->user()->complaints()->create([
        'type'        => $data['type'],
        'message'     => $data['message'],
        'room_number' => $request->user()->room_number,
        'photo_path'  => $photoPath,
    ]);
    AdminNotification::fromTenant($request->user(), 'New complaint', 'sent a complaint (' . $data['type'] . ').');
    return response()->json([
        'ok' => true,
        'message' => 'Complaint sent. We will get back to you soon.',
    ]);
}
}