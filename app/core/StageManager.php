<?php

declare(strict_types=1);

class StageManager
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function getStages(): array
    {
        $stmt = $this->db->query(
            'SELECT
                s.id,
                s.stage_number,
                s.slug,
                s.name,
                s.difficulty,
                s.description,
                s.is_active,
                COALESCE(p.completed, 0) AS completed
             FROM stages s
             LEFT JOIN progress p
                ON p.stage_id = s.id
                AND p.user_id = :user_id
             WHERE s.is_active = 1
             ORDER BY s.stage_number'
        );

        // PDO does not allow named parameters in query().
        // Re-run with prepare() below.
        return [];
    }

    public function getStagesForUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                s.id,
                s.stage_number,
                s.slug,
                s.name,
                s.difficulty,
                s.description,
                s.is_active,
                COALESCE(p.completed, 0) AS completed
             FROM stages s
             LEFT JOIN progress p
                ON p.stage_id = s.id
                AND p.user_id = :user_id
             WHERE s.is_active = 1
             ORDER BY s.stage_number'
        );

        $stmt->execute([
            'user_id' => $userId,
        ]);

        $stages = $stmt->fetchAll();

        foreach ($stages as &$stage) {
            $number = (int) $stage['stage_number'];

            $stage['completed'] = (bool) $stage['completed'];
            $stage['unlocked'] = $this->isUnlocked(
                $userId,
                $number
            );
        }

        return $stages;
    }

    public function getStage(int $stageNumber): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT
                id,
                stage_number,
                slug,
                name,
                difficulty,
                description,
                is_active
             FROM stages
             WHERE stage_number = :stage_number
             LIMIT 1'
        );

        $stmt->execute([
            'stage_number' => $stageNumber,
        ]);

        $stage = $stmt->fetch();

        return $stage ?: null;
    }

    public function isUnlocked(
        int $userId,
        int $stageNumber
    ): bool {
        if ($stageNumber <= 1) {
            return true;
        }

        $stmt = $this->db->prepare(
            'SELECT p.completed
             FROM progress p
             INNER JOIN stages s
                ON s.id = p.stage_id
             WHERE p.user_id = :user_id
               AND s.stage_number = :previous_stage
             LIMIT 1'
        );

        $stmt->execute([
            'user_id' => $userId,
            'previous_stage' => $stageNumber - 1,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function completedCount(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*)
             FROM progress
             WHERE user_id = :user_id
               AND completed = 1'
        );

        $stmt->execute([
            'user_id' => $userId,
        ]);

        return (int) $stmt->fetchColumn();
    }
}