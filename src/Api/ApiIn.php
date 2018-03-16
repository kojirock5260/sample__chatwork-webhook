<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 0:38
 */

namespace Kojirock\Api;

interface ApiIn
{
    /**
     * APIコールを行う
     *
     * @param string $message
     * @param array  $params
     * @return array
     */
    public function call($message, array $params = []);
}