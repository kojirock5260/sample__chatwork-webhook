<?php
/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/03/03
 * Time: 15:36
 */

namespace Kojirock\Api;

class ZipCloudApi implements ApiIn
{
    const BASE_URL = 'http://zipcloud.ibsnet.co.jp/api/search';

    /**
     * APIコールを実行
     *
     * @param string $pref_code
     * @param array $params
     * @return array
     */
    public function call($pref_code, array $params = [])
    {
        $url = self::BASE_URL . '?zipcode=' . $pref_code;

        return file_get_contents($url);
    }
}