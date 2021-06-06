<?php

namespace X0i\Demo\Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);

/**
 * Class WorkdayTable
 * @package X0i\Demo\Models
 */
class WorkdayTable extends Entity\DataManager {

    /**
     * DB table name for entity.
     * @return string
     */
    public static function getTableName() : string
    {
        return 'x0i_demo_workday';
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
                'title' => Loc::getMessage('ENTITY_WORKDAY_FIELD_ID'),
            ]),
            new Fields\IntegerField('PROFILE_ID', [
                'required' => true,
                'title' => Loc::getMessage('ENTITY_WORKDAY_FIELD_PROFILE_ID'),
            ]),
            new Fields\DatetimeField('DATE_START', [
                'title' => Loc::getMessage('ENTITY_WORKDAY_FIELD_DATE_START'),
            ]),
            new Fields\DatetimeField('DATE_STOP', [
                'title' => Loc::getMessage('ENTITY_WORKDAY_FIELD_DATE_STOP'),
            ]),
        ];
    }

}