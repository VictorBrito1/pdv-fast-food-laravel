<?php

namespace App\Enums;

/**
 * PaymentTypeType
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
final class PaymentTypeType
{
    const CASH = 'cash';
    const CREDIT_CARD_CREDIT = 'credit_card_credit';
    const CREDIT_CARD_DEBIT = 'credit_card_debit';

    /**
     * @return string
     */
    public static function getValuesAsStringWithComma()
    {
        return sprintf("%s,%s,%s", self::CASH, self::CREDIT_CARD_CREDIT, self::CREDIT_CARD_DEBIT);
    }
}
