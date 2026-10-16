<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Support\DormStats;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function home(): View
    {
        return view('tenant.home', [
            'stats' => DormStats::get(),
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
        'tenants' => User::where('role', 'tenant')
            ->whereNotNull('room_number')
            ->get()
            ->groupBy('room_number'),
    ]);
}
    public function payments(): View { return view('tenant.payments'); }
    public function announcement(): View { return view('tenant.announcement'); }
    public function location(): View { return view('tenant.location'); }
    public function settings(): View { return view('tenant.settings'); }
    public function marketplace(): View { return view('tenant.marketplace'); }
    public function calendar(): View { return view('tenant.calendar'); }
}