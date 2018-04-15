<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/19
 * Time: 15:43
 */


class Application{
    public static function main(){
        self::registe();
    }
    public static function registe(){
        spl_autoload_register("Application::loadClass");
    }
    public static function loadClass($class){
        $class=str_replace('\\', '/', $class);
        $class="./".$class.".php";
//        require_once $class;
        var_dump($class);
    }
}
Application::main();

//$i = 1;
//$i +=2;
//echo $i;