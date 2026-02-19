<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'type',
        'title',
        'message',
        'source_type',
        'source_id',
        'related_url',
        'is_read',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function syncScheduledAlerts(): int
    {
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');

        $dueAlerts = $this->db->table('document_alerts da')
            ->select('da.id as alert_id, da.document_id, da.alert_date, da.alert_time, da.description, d.title as document_title')
            ->join('documents d', 'd.id = da.document_id', 'left')
            ->where('da.is_notified', 0)
            ->groupStart()
                ->where('da.alert_date <', $today)
                ->orGroupStart()
                    ->where('da.alert_date', $today)
                    ->groupStart()
                        ->where('da.alert_time IS NULL', null, false)
                        ->orWhere('da.alert_time <=', $nowTime)
                    ->groupEnd()
                ->groupEnd()
            ->groupEnd()
            ->orderBy('da.alert_date', 'ASC')
            ->orderBy('da.alert_time', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($dueAlerts)) {
            return 0;
        }

        $created = 0;
        $processedAlertIds = [];

        foreach ($dueAlerts as $alert) {
            $processedAlertIds[] = (int) $alert['alert_id'];

            $existing = $this->where('source_type', 'document_alert')
                ->where('source_id', (int) $alert['alert_id'])
                ->first();

            if ($existing) {
                continue;
            }

            $message = 'Scheduled alert for "' . ($alert['document_title'] ?? 'Document') . '" on ' . date('M d, Y', strtotime($alert['alert_date']));
            if (!empty($alert['alert_time'])) {
                $message .= ' at ' . date('h:i A', strtotime($alert['alert_time']));
            }
            if (!empty($alert['description'])) {
                $message .= '. ' . trim((string) $alert['description']);
            }

            $inserted = $this->insert([
                'type' => 'schedule_alert',
                'title' => 'Document Schedule Alert',
                'message' => $message,
                'source_type' => 'document_alert',
                'source_id' => (int) $alert['alert_id'],
                'related_url' => site_url('documents/details/' . (int) $alert['document_id']),
                'is_read' => 0,
            ]);

            if ($inserted) {
                $created++;
            }
        }

        if (!empty($processedAlertIds)) {
            $this->db->table('document_alerts')
                ->whereIn('id', $processedAlertIds)
                ->update(['is_notified' => 1]);
        }

        return $created;
    }

    public function getUnreadCount(): int
    {
        return (int) $this->where('is_read', 0)->countAllResults();
    }

    public function markAllAsRead(): bool
    {
        return (bool) $this->where('is_read', 0)->set(['is_read' => 1])->update();
    }
}
