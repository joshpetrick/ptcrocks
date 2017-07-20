<?php
/**
 * Created by IntelliJ IDEA.
 * User: benjaminshoemaker
 * Date: 7/19/17
 * Time: 10:55 PM
 */

namespace Service;


class ChainCoinService
{
    private $username;

    function __construct($username)
    {
        $this->username = escapeshellarg($username);
    }

    public function getInfo(){
        /* Find Our Scripts Directory */
        $path = $this->getScriptPath() . 'getInfo.sh';

        $resultVar = shell_exec('sudo -u ' . $this->username . ' ' . $path);

        $jsonResult = json_decode($resultVar, true);

        return $jsonResult;
    }

    public function transferCoins($address, $amount){
        /* Find Our Scripts Directory */
        $path = $this->getInfo() . 'transferCoins.sh';
        $resultVar = shell_exec('sudo -u ' . $this->username . ' ' . $path . ' ' . $address . ' ' . $amount . ' 2>&1');
        return $resultVar;
    }

    private function getScriptPath(){
        return str_replace('/web', '/', $_SERVER['DOCUMENT_ROOT']) . 'scripts/';
    }
}