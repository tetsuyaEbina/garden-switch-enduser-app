<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MasterData\Hall;
use App\Models\MasterData\Machine;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('user')->user();
        if($user->has_custom_flow === 1){

            $idsRaw = $user->viewable_hall_id_list;
            $ids    = is_array($idsRaw) ? $idsRaw : json_decode($idsRaw ?? '[]', true);
            if (!is_array($ids)) $ids = [];

            $ids = array_values(array_filter($ids, fn($v) => $v !== null && $v !== ''));
            $ids = array_map('strval', $ids);

            $halls = collect();
            if (!empty($ids)) {
                $halls = Hall::query()
                    ->whereIn('hall_id', $ids)
                    ->orderBy('hall_name')             // name カラム想定
                    ->get(['hall_id', 'hall_name']);
            }
            $machines = Machine::select('machine_id','machine_display_name')->get();

            return view('user.home', compact('halls', 'machines'));
        }
        return view('user.home');
    }
}
