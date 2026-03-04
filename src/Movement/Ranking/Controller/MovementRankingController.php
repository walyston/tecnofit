<?php

namespace Movement\Ranking\Controller;

use Movement\Ranking\Application\GetMovementRanking;

final class MovementRankingController
{
    public function __construct(
        private GetMovementRanking $useCase
    ) {}

    public function handle(string $identifier): array
    {
        return $this->useCase
            ->execute($identifier)
            ->toArray();
    }
}
