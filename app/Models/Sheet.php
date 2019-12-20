<?php

namespace App\Models;

/**
 * An Eloquent Model: 'Sheet'
 *
 * @property int $id
 * @property string $status
 * @property string $file
 */
class Sheet extends Model
{
    const PENDING = 'pending';

    const REJECTED = 'rejected';

    const FULFILLED = 'fulfilled';
}
