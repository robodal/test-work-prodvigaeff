<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\File;

Loc::loadMessages(__FILE__);

/**
 * Class mozaika_devenv
 */
class x0i_demo extends \CModule
{

    /**
     * Installer constructor
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');
        $this->MODULE_ID = 'x0i.demo';
        $this->MODULE_NAME = Loc::getMessage('X0I_DEMO_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('X0I_DEMO_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = 'OlegDanilkin';
        $this->PARTNER_URI = 'https://x0i.ru/';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'] ?? '0.0.0';
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'] ?? '2020-01-01 00:00:00';
    }

    /**
     * Run install
     */
    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
    }

    /**
     * Run uninstall
     */
    public function DoUninstall()
    {
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    /**
     * Install DB
     * @return bool
     */
    function InstallDB() : bool
    {
        $file = new File(__DIR__ . '/db/' . $GLOBALS['DBType'] . '/install.sql');
        if ($file->isExists()) {
            Application::getConnection()->executeSqlBatch($file->getContents());
        }
        return true;
    }

    /**
     * UnInstall DB
     * @return bool
     */
    function UnInstallDB() : bool
    {
        $file = new File(__DIR__ . '/db/' . $GLOBALS['DBType'] . '/uninstall.sql');
        if ($file->isExists()) {
            Application::getConnection()->executeSqlBatch($file->getContents());
        }
        return true;
    }



}


