<?php

namespace X0i\Demo;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Agents
{

    /**
     * Auto close days for all profiles
     * @return string
     */
    public static function closeDays() : string
    {
        \Bitrix\Main\Application::getConnection()->query("
            UPDATE `x0i_demo_profile` `PROFILE`
                   LEFT JOIN `x0i_demo_workday` `WORKDAY` ON (
                       `WORKDAY`.`PROFILE_ID` = `PROFILE`.`ID` AND
                       DATE(CONVERT_TZ(`WORKDAY`.`DATE_START`, @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00'))) <
                           DATE(CONVERT_TZ(NOW(), @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00')))
                   )
            SET `WORKDAY`.`DATE_STOP` = CONVERT_TZ(DATE_ADD(DATE(CONVERT_TZ(`WORKDAY`.`DATE_START`, @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00'))), INTERVAL 1 DAY), CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00'), @@session.time_zone)
             WHERE `WORKDAY`.`DATE_STOP` IS NULL
               AND `WORKDAY`.`DATE_START` IS NOT NULL
        ");
        return '\\' . __METHOD__ . '();';
    }

    /**
     * Auto check lateness
     * @return string
     */
    public static function saveLateness() : string
    {
        \Bitrix\Main\Application::getConnection()->query("
            REPLACE INTO `x0i_demo_lateness` (`PROFILE_ID`, `DATE`)
            SELECT `PROFILE`.`ID`, DATE(CONVERT_TZ(NOW(), @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00')))
            FROM `x0i_demo_profile` `PROFILE`
                 LEFT JOIN `x0i_demo_workday` `WORKDAY` ON (
                     `WORKDAY`.`PROFILE_ID` = `PROFILE`.`ID` AND
                     DATE(CONVERT_TZ(`WORKDAY`.`DATE_START`, @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00'))) =
                         DATE(CONVERT_TZ(NOW(), @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00')))
                 )
            WHERE HOUR(CONVERT_TZ(NOW(), @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00'))) >= 8
             AND `WORKDAY`.`DATE_START` IS NULL
        ");
        return '\\' . __METHOD__ . '();';
    }

}