<?php

namespace App\Contracts;

use Carbon\Carbon;
use DateTime;

interface ActivityData
{

    public function getFlightNumber():string;
    public function getType();
    public function getDate():DateTime;
    public function getFrom():string;
    public function getTo():string;
    public function getStart():?Carbon;
    public function getEnd():?Carbon;
}
