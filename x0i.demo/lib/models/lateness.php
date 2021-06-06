<?php

namespace X0i\Demo\Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);

/**
 * Class LatenessTable
 * @package X0i\Demo\Models
 */
class LatenessTable extends Entity\DataManager {

    /**
     * DB table name for entity.
     * @return string
     */
    public static function getTableName() : string
    {
        return 'x0i_demo_lateness';
    }

    /**
     * Entity map definition.
     * @return array
     */
    public static function getMap() : array
    {
        return [
            new Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('ENTITY_LATENESS_FIELD_ID'),
            ]),
            new Fields\IntegerField('PROFILE_ID', [
                'title' => Loc::getMessage('ENTITY_LATENESS_FIELD_PROFILE_ID'),
            ]),
            new Fields\DatetimeField('DATE', [
                'title' => Loc::getMessage('ENTITY_LATENESS_FIELD_DATE'),
            ]),
        ];
    }

}