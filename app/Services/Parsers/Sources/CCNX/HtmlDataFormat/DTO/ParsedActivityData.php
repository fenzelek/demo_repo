<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\DTO;

use App\Contracts\ActivityData;
use App\Models\ValueObjects\ActivityType;
use Carbon\Carbon;
use DateTime;

class ParsedActivityData implements ActivityData
{

    private ActivityType $type;
    private DateTime $date;
    private string $from;
    private string $to;
    private ?Carbon $start;
    private ?Carbon $end;
    private string $flightNumber;

    public function __construct(string $flightNumber, ActivityType $type, DateTime $date, string $from, string $to, ?Carbon $start, ?Carbon $end)
    {
        $this->flightNumber = $flightNumber;
        $this->type = $type;
        $this->date = $date;
        $this->from = $from;
        $this->to = $to;
        $this->start = $start;
        $this->end = $end;

    }
    public function getType()
    {
        return $this->type;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getStart(): ?Carbon
    {
        return $this->start;
    }

    public function getEnd(): ?Carbon
    {
        return $this->end;
    }

    public function getFlightNumber(): string
    {
        return $this->flightNumber;
    }
}
