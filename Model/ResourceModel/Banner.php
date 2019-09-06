<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 3:34 PM
 */

namespace SM\PWABanner\Model\ResourceModel;


class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sm_pwa_banner', 'banner_id');
    }
}