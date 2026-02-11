<?php

namespace App\Observers;

use App\Models\Lead;

class LeadObserver
{
    public function creating(Lead $lead): void
    {
        // Нормализация email
        $lead->email = strtolower(trim($lead->email));
        
        // Автоматическое заполнение IP, если не указан
        if (!$lead->ip && request()) {
            $lead->ip = request()->ip();
        }
    }
}
