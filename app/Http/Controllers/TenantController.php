<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Support\DormStats;
use Illuminate\View\View;
use App\Models\AdminMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function home(): View
    {
        return view('tenant.home', [
            'stats' => DormStats::get(),
             'featured' => DormStats::featuredRooms(),
        ]);
    }

    public function profile(): View { return view('tenant.profile'); }

  public function dorm(): View
{
    return view('tenant.dorm', [
        'overview' => DormStats::overview(),
        'rooms'    => DormStats::rooms(),
    ]);
}

   public function rooms(): View
{
    return view('tenant.rooms', [
        'summary' => DormStats::listing(),
        'rooms' => Room::orderBy('room_number')->get(),
         'fees'    => DormStats::fees(), 
        'tenants' => User::where('role', 'tenant')
            ->whereNotNull('room_number')
            ->get()
            ->groupBy('room_number'),
    ]);
}
public function sendMessage(Request $request): JsonResponse
{
    $data = $request->validate([
        'message' => ['required', 'string', 'min:2', 'max:1000'],
        'room'    => ['nullable', 'string', 'max:10'],
    ]);

    AdminMessage::create([
        'tenant_id'   => $request->user()->id,
        'room_number' => $data['room'] ?? $request->user()->room_number,
        'message'     => trim($data['message']),
    ]);

    return response()->json(['ok' => true]);
}
    public function payments(): View { return view('tenant.payments'); }
    public function announcement(): View { return view('tenant.announcement'); }
    public function location(): View { return view('tenant.location'); }
    public function settings(): View { return view('tenant.settings'); }
    public function marketplace(): View { return view('tenant.marketplace'); }
    public function calendar(): View { return view('tenant.calendar'); }
}