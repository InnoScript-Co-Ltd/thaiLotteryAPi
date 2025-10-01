<?php

namespace App\Traits;

trait BasicAudit
{
    protected static function bootBasicAudit()
    {
        $self = new static;

        static::deleting(function ($model) use ($self) {
            if ($self->isSoftDeleteEnabled()) {
                $model->save();
            }
        });
    }

    public function isSoftDeleteEnabled()
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this)) && ! $this->forceDeleting;
    }
}
