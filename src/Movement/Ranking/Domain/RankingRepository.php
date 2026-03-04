<?php

namespace Movement\Ranking\Domain;

interface RankingRepository
{
    public function getRankingByMovementIdentifier(string $identifier): MovementRankingResult;
}
