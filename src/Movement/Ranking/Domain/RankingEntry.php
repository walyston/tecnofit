<?php

namespace Movement\Ranking\Domain;

final class RankingEntry
{
    public function __construct(
        private int $position,
        private string $userName,
        private float $personalRecord,
        private string $date
    ) {}

    public function toArray(): array
    {
        return [
            'position' => $this->position,
            'user' => $this->userName,
            'personal_record' => $this->personalRecord,
            'date' => $this->date
        ];
    }
}
