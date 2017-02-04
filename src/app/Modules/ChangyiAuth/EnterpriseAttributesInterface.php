<?php namespace App\Modules\ChangyiAuth;
/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 2016/11/1
 */

interface EnterpriseAttributesInterface
{
    public function setMobile($mobile);

    public function setEmail($email);

    public function setPassword($password);

    public function setCompanyName($companyName);

    public function setEpKey($epKey);

    public function setRealName($realName);

    public function setIndustry($industry);

    public function getMobile();

    public function getEmail();

    public function getPassword();

    public function getCompanyName();

    public function getEpKey();

    public function getRealName();

    public function getIndustry();

}