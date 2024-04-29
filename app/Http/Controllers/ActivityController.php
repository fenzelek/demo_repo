<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;


class ActivityController extends Controller
{
    public function index()
    {
        return view('activity');
    }

    public function show(Request $request)
    {
        $query = Activity::query();

        if ($request->has('startDate') && $request->has('endDate')) {
           // $query->whereBetween('Date', [$request->startDate, $request->endDate]);
            $query->whereDate('Date', '>=', $request->startDate)->whereDate('Date', '<=', $request->endDate);
        }

        if ($request->has('actionType')) {
            $query->where('Type', $request->actionType);
        }

        if ($request->has('locationFrom')) {
            $query->where('From', $request->locationFrom);
        }

        if ($request->has('locationTo')) {
            $query->where('To', $request->locationTo);
        }

        $activities = $query->get();
        //return response()->json($activities);

        return ActivityResource::collection($activities);
    }
}
