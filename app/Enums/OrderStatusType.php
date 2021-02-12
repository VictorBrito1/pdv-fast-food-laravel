<?php

namespace App\Enums;

/**
 * OrderStatusType
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
final class OrderStatusType
{
    const OPEN = 'open';
    const SENT_TO_THE_KITCHEN = 'sent_to_the_kitchen';
    const READY = 'ready';
    const FINISHED = 'finished';
}
