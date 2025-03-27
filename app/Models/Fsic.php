<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fsic extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'issue_date', 'expiration_date', 'amount', 'or_number', 'inspector_id', 'marshall_id'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(Inspector::class);
    }

    public function marshall(): BelongsTo
    {
        return $this->belongsTo(Marshall::class);
    }
}
