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
            'SELECT id, stage_number, slug, name, difficulty, description, is_active
             FROM stages
             WHERE is_active = 1
             ORDER BY stage_number'
        );

        return $stmt->fetchAll();
    }

    public function getStage(int $stageNumber): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, stage_number, slug, name, difficulty, description, is_active
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

    public function isUnlocked(int $userId, int $stageNumber): bool
    {
        if ($stageNumber <= 1) {
            return true;
        }

        $stmt = $this->db->prepare(
            'SELECT p.completed
             FROM progress p
             INNER JOIN stages s ON s.id = p.stage_id
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
}
