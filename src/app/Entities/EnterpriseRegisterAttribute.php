<?php namespace App\Entities;

use App\Modules\ChangyiAuth\EnterpriseAttributesInterface;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 2016/11/1
 */
class EnterpriseRegisterAttribute implements EnterpriseAttributesInterface
{

    public $mobile;

    public $email;

    public $password;

    public $companyName;

    public $epKey;

    public $realName;

    public $industry;


    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }


    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function setEpKey($epKey)
    {
        $this->epKey = $epKey;
        return $this;
    }

    public function setRealName($realName)
    {
        $this->realName = $realName;
        return $this;
    }

    public function setIndustry($industry)
    {
        $this->industry = $industry;
        return $this;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getEpKey()
    {
        return $this->epKey;
    }

    public function getRealName()
    {
        return $this->realName;
    }

    public function getIndustry()
    {
        return $this->industry;
    }
}