<?php
namespace X0i\Demo\Controller;

use Bitrix\Main\Error;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Type\DateTime;
use X0i\Demo\Models\WorkdayTable;
use X0i\Demo\Models\WorkdayPauseTable;

class WorkTime extends \Bitrix\Main\Engine\Controller
{

    /**
     * Action start workday by profileId
     *
     * @param int $profileId
     */
    public function startAction(int $profileId)
    {
        // Get actual workday for selected profile
        $workday = static::getActualWorkday($profileId);

        // Profile not found
        if (empty($workday['OFFSET'])) {
            $this->addError(new Error('Profile not found or error configured profile timezone'));
            return null;
        }

        // Day already started
        if (!empty($workday['ID'])) {
            $this->addError(new Error('Current workday already started'));
            return null;
        }

        // Start day
        $res = WorkdayTable::add([
            'PROFILE_ID' => $profileId,
            'DATE_START' => new DateTime(),
        ]);

        // Check result and return
        if ($res->isSuccess()) {
            return $res->getId();
        }
        foreach ($res->getErrorMessages() as $errorMessage) {
            $this->addError(new Error($errorMessage));
        }
        return null;
    }

    /**
     * Action stop workday by profileId
     *
     * @param int $profileId
     */
    public function stopAction(int $profileId)
    {
        // Get actual workday for selected profile
        $workday = static::getActualWorkday($profileId);

        // Profile not found
        if (empty($workday['OFFSET'])) {
            $this->addError(new Error('Profile not found or error configured profile timezone'));
            return null;
        }

        // Day don't started
        if (empty($workday['ID'])) {
            $this->addError(new Error('Current workday has not started'));
            return null;
        }

        // Day already stopped
        if (!empty($workday['DATE_STOP'])) {
            $this->addError(new Error('Current workday already stopped'));
            return null;
        }

        // Stop day
        $res = WorkdayTable::update($workday['ID'], [
            'DATE_STOP' => new DateTime(),
        ]);

        // Check result and return
        if ($res->isSuccess()) {
            return $res->getId();
        }
        foreach ($res->getErrorMessages() as $errorMessage) {
            $this->addError(new Error($errorMessage));
        }
        return null;
    }

    /**
     * Action pause workday by profileId
     *
     * @param int $profileId
     */
    public function pauseAction(int $profileId)
    {
        // Get actual workday for selected profile
        $workday = static::getActualWorkday($profileId);

        // Profile not found
        if (empty($workday['OFFSET'])) {
            $this->addError(new Error('Profile not found or error configured profile timezone'));
            return null;
        }

        // Day don't started
        if (empty($workday['ID'])) {
            $this->addError(new Error('Current workday has not started'));
            return null;
        }

        // Day already stopped
        if (!empty($workday['DATE_STOP'])) {
            $this->addError(new Error('Current workday already stopped'));
            return null;
        }

        //TODO: опущу проверки по таблице пауз, т.к. она по заданию дальше нигде не участвует...

        // Pause day
        $res = WorkdayPauseTable::add([
            'WORKDAY_ID' => $workday['ID'],
            'DATE_START' => new DateTime(),
        ]);

        // Check result and return
        if ($res->isSuccess()) {
            return $res->getId();
        }
        foreach ($res->getErrorMessages() as $errorMessage) {
            $this->addError(new Error($errorMessage));
        }
        return null;
    }

    /**
     * Action resume workday by profileId
     *
     * @param int $profileId
     */
    public function resumeAction(int $profileId)
    {
        // Get actual workday for selected profile
        $workday = static::getActualWorkday($profileId);

        // Profile not found
        if (empty($workday['OFFSET'])) {
            $this->addError(new Error('Profile not found or error configured profile timezone'));
            return null;
        }

        // Day don't started
        if (empty($workday['ID'])) {
            $this->addError(new Error('Current workday has not started'));
            return null;
        }

        // Day already stopped
        if (!empty($workday['DATE_STOP'])) {
            $this->addError(new Error('Current workday already stopped'));
            return null;
        }

        //TODO: опущу проверки по таблице пауз, т.к. она по заданию дальше нигде не участвует...

        // Select actual pause
        $pause = WorkdayPauseTable::query()
            ->setSelect(['ID'])
            ->addFilter('WORKDAY_ID', $workday['ID'])
            ->addFilter('DATE_STOP', false)
            ->fetch();

        // Day don't paused
        if (empty($pause['ID'])) {
            $this->addError(new Error('Current workday has not paused'));
            return null;
        }

        // Resume day
        $res = WorkdayPauseTable::update($pause['ID'], [
            'DATE_STOP' => new DateTime(),
        ]);

        // Check result and return
        if ($res->isSuccess()) {
            return $res->getId();
        }
        foreach ($res->getErrorMessages() as $errorMessage) {
            $this->addError(new Error($errorMessage));
        }
        return null;
    }

    /**
     * Action start workday by profileId
     *
     * @param int $profileId
     */
    public static function getActualWorkday(int $profileId)
    {
        // Get actual workday for selected profile
        $db = \Bitrix\Main\Application::getConnection();
        return $db->query("
            SELECT `WORKDAY`.`ID`, `WORKDAY`.`DATE_START`, `WORKDAY`.`DATE_STOP`, `PROFILE`.`OFFSET`
            FROM `x0i_demo_profile` `PROFILE`
                 LEFT JOIN `x0i_demo_workday` `WORKDAY` ON (
                     `WORKDAY`.`PROFILE_ID` = `PROFILE`.`ID` AND
                     DATE(CONVERT_TZ(`WORKDAY`.`DATE_START`, @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00'))) =
                       DATE(CONVERT_TZ(NOW(), @@session.time_zone, CONCAT(LEFT(`PROFILE`.`OFFSET`, 3), ':00')))
                 )
            WHERE `PROFILE`.`ID` = {$profileId}
        ")->fetch();
    }

    /**
     * Configure actions methods
     * @return array
     */
    public function configureActions() : array
    {
        $rules = [
            'prefilters' => [
                new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET]),
                new ActionFilter\Csrf(false),
            ],
            'postfilters' => [],
        ];
        return [
            'start' => $rules,
            'stop' => $rules,
            'pause' => $rules,
            'resume' => $rules,
        ];
    }

}