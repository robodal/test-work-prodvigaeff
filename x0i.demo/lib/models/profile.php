<?php

namespace X0i\Demo\Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);

/**
 * Class ProfileTable
 * @package X0i\Demo\Models
 */
class ProfileTable extends Entity\DataManager {

    /**
     * DB table name for entity.
     * @return string
     */
    public static function getTableName() : string
    {
        return 'x0i_demo_profile';
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
                'title' => Loc::getMessage('ENTITY_PROFILE_FIELD_ID'),
            ]),
            new Fields\TextField('LOGIN', [
                'required' => true,
                'title' => Loc::getMessage('ENTITY_PROFILE_FIELD_LOGIN'),
                'validation' => function() {
                    return [
                        new Entity\Validator\Length(null, 255),
                    ];
                },
            ]),
            new Fields\TextField('NAME', [
                'title' => Loc::getMessage('ENTITY_PROFILE_FIELD_NAME'),
                'validation' => function() {
                    return [
                        new Entity\Validator\Length(null, 255),
                    ];
                },
            ]),
            new Fields\TextField('LAST_NAME', [
                'title' => Loc::getMessage('ENTITY_PROFILE_FIELD_LAST_NAME'),
                'validation' => function() {
                    return [
                        new Entity\Validator\Length(null, 255),
                    ];
                },
            ]),
            new Fields\TextField('OFFSET', [
                'title' => Loc::getMessage('ENTITY_PROFILE_FIELD_OFFSET'),
                'validation' => function() {
                    return [
                        new Entity\Validator\RegExp('#^[+-][01]\d00$#'),
                    ];
                },
            ]),
        ];
    }

}