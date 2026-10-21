<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'message.required' => 'Please type your suggestion.',
            'message.min'      => 'Your suggestion is too short.',
        ]);

        Suggestion::create([
            'user_id'     => $request->user()->id,
            'room_number' => $request->user()->room_number,
            'message'     => $data['message'],
        ]);

        return response()->json(['ok' => true]);
    }
}