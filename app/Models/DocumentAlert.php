<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentAlert extends Model
{
    protected $table = 'document_alerts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'document_id',
        'alert_date',
        'alert_time',
        'description',
        'is_notified',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'document_id' => 'required|integer',
        'alert_date' => 'required|valid_date',
        'alert_time' => 'permit_empty|regex_match[/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/]',
        'description' => 'permit_empty|string|max_length[5000]',
    ];

    public function getAlertsByDocument($documentId)
    {
        return $this->where('document_id', $documentId)
            ->orderBy('alert_date', 'ASC')
            ->orderBy('alert_time', 'ASC')
            ->findAll();
    }

    public function getUpcomingAlerts($days = 7)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+$days days"));

        return $this->select('document_alerts.*, documents.title as document_title')
            ->join('documents', 'documents.id = document_alerts.document_id', 'left')
            ->where('alert_date >=', $startDate)
            ->where('alert_date <=', $endDate)
            ->where('is_notified', 0)
            ->orderBy('alert_date', 'ASC')
            ->orderBy('alert_time', 'ASC')
            ->findAll();
    }

    public function getAlertsForMonth($year, $month)
    {
        $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->select('document_alerts.*, documents.title as document_title')
            ->join('documents', 'documents.id = document_alerts.document_id', 'left')
            ->where('alert_date >=', $startDate)
            ->where('alert_date <=', $endDate)
            ->orderBy('alert_date', 'ASC')
            ->findAll();
    }

    public function markAsNotified($id)
    {
        return $this->update($id, ['is_notified' => 1]);
    }
}
