<?php

namespace Movement\Ranking\Domain;

final class MovementRankingResult
{
    public function __construct(
        private string $movementName,
        private array $entries
    ) {}

    public function toArray(): array
    {
        return [
            'movement' => $this->movementName,
            'ranking' => array_map(
                fn($entry) => $entry->toArray(),
                $this->entries
            )
        ];
    }
}
