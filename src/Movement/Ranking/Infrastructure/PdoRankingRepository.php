<?php

namespace Movement\Ranking\Infrastructure;

use PDO;
use RuntimeException;
use Movement\Ranking\Domain\RankingRepository;
use Movement\Ranking\Domain\RankingEntry;
use Movement\Ranking\Domain\MovementRankingResult;

class PdoRankingRepository implements RankingRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getRankingByMovementIdentifier(string $identifier): MovementRankingResult
    {
        
        $movementStmt = $this->pdo->prepare("
            SELECT id, name
            FROM movement
            WHERE id = :id OR name = :name
            LIMIT 1
        ");

        $movementStmt->execute([
            'id'   => ctype_digit($identifier) ? (int) $identifier : 0,
            'name' => $identifier
        ]);

        $movement = $movementStmt->fetch(PDO::FETCH_ASSOC);

        if (!$movement) {
            throw new RuntimeException('Movement not found');
        }

        $movementId   = (int) $movement['id'];
        $movementName = $movement['name'];

        $stmt = $this->pdo->prepare("
            WITH best_records AS (
                SELECT
                    pr.user_id,
                    u.name,
                    pr.value,
                    pr.date,
                    ROW_NUMBER() OVER (
                        PARTITION BY pr.user_id
                        ORDER BY pr.value DESC
                    ) AS rn
                FROM personal_record pr
                INNER JOIN user u ON u.id = pr.user_id
                WHERE pr.movement_id = :movementId
            )
            SELECT
                name,
                value,
                date,
                RANK() OVER (ORDER BY value DESC) AS position
            FROM best_records
            WHERE rn = 1
            ORDER BY value DESC
        ");

        $stmt->execute([
            'movementId' => $movementId
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $entries = [];

        foreach ($rows as $row) {
            $entries[] = new RankingEntry(
                (int) $row['position'],
                $row['name'],
                (float) $row['value'],
                $row['date']
            );
        }

        return new MovementRankingResult(
            $movementName,
            $entries
        );
    }
}
