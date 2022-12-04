<?php 
namespace ABOP\Traits;

trait Singleton {    

    /**
     * L'instance enregistré en mémoire statique
     *
     * @var stdClass
     */
    public static $instance;

    protected function __construct() { }
    
    /**
     * Va chercher l'instance de la classe, si elle n'existe pas, va créer l'instance avant de la retourner
     *
     * @return void
     */
    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}