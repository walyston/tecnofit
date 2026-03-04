<?php

namespace Movement\Ranking\Application;

use Movement\Ranking\Domain\RankingRepository;
use Movement\Ranking\Domain\MovementRankingResult;

final class GetMovementRanking
{
    public function __construct(
        private RankingRepository $repository
    ) {}

    public function execute(string $identifier): MovementRankingResult
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException('Movement identifier required');
        }

        return $this->repository->getRankingByMovementIdentifier($identifier);
    }
}
