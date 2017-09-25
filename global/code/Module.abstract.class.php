<?php

namespace FormTools;

use ReflectionClass;


/**
 * Our base class for all Modules. All Form Tools modules need to extend this abstract class to be recognized by the
 * script.
 * @author Ben Keen <ben.keen@gmail.com>
 * @package Core
 * @abstract
 */
abstract class Module {

    // REQUIRED
    protected $moduleName;
    protected $moduleDesc;
    protected $author;
    protected $authorEmail;
    protected $authorLink;
    protected $version;
    protected $date;

    // OPTIONAL
    protected $originLanguage = "en_us";
    protected $nav = array();

    /**
     * An array of JS files included for this module. Files defined here will automatically be included on all module pages.
     * @var array
     */
    protected $jsFiles = array();

    /**
     * An array of JS files included for this module. Files defined here will automatically be included on all module pages.
     * @var array
     */
    protected $cssFiles = array();

    /**
     * Contains all strings for the current language. This is populated automatically on instantiation and
     * contains the strings for the currently selected language.
     * @var array
     */
    protected $L = array();
    private $currentLangFound;


    /**
     * The default constructor. Automatically populates the $L member var with whatever language is currently being
     * used. If a Module defines its own constructor, it should always call the parent constructor as well to ensure
     * $L is populated. ( parent::__construct(); )
     */
    public function __construct($lang) {

        // a little magic to find the current instantiated class's folder
        $currClass = new ReflectionClass(get_class($this));
        $currClassFolder = dirname($currClass->getFileName());

        $currentLangFile = realpath($currClassFolder . "/../lang/{$lang}.php");
        $defaultLangFile = realpath($currClassFolder . "/../lang/{$this->originLanguage}.php");

        if (file_exists($currentLangFile)) {
            require_once($currentLangFile);
            $this->currentLangFound = true;
        } else if (file_exists($defaultLangFile)) {
            require_once($defaultLangFile);
            $this->currentLangFound = true;
        }

        if (isset($L)) {
            $this->L = $L;
        }
    }

    /**
     * This is called once during the initial installation of the script, or when the installation is reset (which is
     * effectively a fresh install). It is called AFTER the Core tables are installed, and you can rely
     * on Core::$db having been initialized and the database connection having been set up.
     *
     * @return array [0] success / error
     * 				 [1] the error message, if there was a problem
     */
    public static function install() {
        return array(true, "");
    }

    public static function uninstall() {
        return array(true, "");
    }

    public static function upgrade($old_module_version) {
        return array(true, "");
    }


    // non-overridable getters

    public final function displayPage($template, $page_vars = array()) {

        // add in the JS and CSS files
        $page_vars["js_files"] = self::getJSFiles();
        $page_vars["css_files"] = self::getCSSFiles();

        Themes::displayModulePage($template, $page_vars);
    }

    public final function getModuleName() {
        return $this->moduleName;
    }

    public final function getModuleDesc() {
        return $this->moduleDesc;
    }

    public final function getAuthor() {
        return $this->author;
    }

    public final function getAuthorEmail() {
        return $this->authorEmail;
    }

    public final function getAuthorLink() {
        return $this->authorLink;
    }

    public final function getDate() {
        return $this->date;
    }

    public final function getVersion() {
        return $this->version;
    }

    public final function getOriginLang() {
        return $this->originLanguage;
    }

    public final function getModuleNav() {
        return $this->nav;
    }

    /**
     * Returns a list of all javascript files for this module.
     * @return array
     */
    public final function getJSFiles() {
        return $this->jsFiles;
    }

    /**
     * Returns a list of all javascript files for this module.
     * @return array
     */
    public final function getCSSFiles() {
        return $this->cssFiles;
    }

    /**
     * Returns the language strings
     * @return array
     */
    public final function getLangStrings() {
        return $this->L;
    }

    /**
     * Returns the language strings
     * @return bool
     */
    public final function isCurrentLangFound() {
        return $this->currentLangFound;
    }

}
