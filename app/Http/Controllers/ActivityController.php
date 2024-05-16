<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;


class ActivityController extends Controller
{
    /**
     * @group Activities
     *
     * Display Activities listing page with input filters
     */
    public function index()
    {
        return view('activity');
    }

    /**
     * @group Activities
     *
     * Get All or filtered data for activities
     *
     * @queryParam filter[startDate] Filter the activities to specific start date period. filter[startDate]=06/01/2022
     * @queryParam filter[endDate] Filter the activities to specific end date period. filter[endDate]=06/01/2022
     * @queryParam filter[actionType] Filter the activities to specific action type like Flight (FLT), CheckIn(CI), Checkout (CO) Day Off (DO), Standby (SBY), Unknown(UNK). filter[endDate]=FLT
     * @queryParam filter[locationFrom] Filter the activities by starting Location shortcut. filter[locationFrom]=KRP
     * @queryParam filter[locationTo] Filter the activities by ending Location shortcut. filter[locationFrom]=KRP
     */
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

        return ActivityResource::collection($activities);
    }
}
