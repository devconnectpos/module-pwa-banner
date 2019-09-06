<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 3:36 PM
 */

namespace SM\PWABanner\Model\ResourceModel\Banner;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'banner_id';

    public function _construct()
    {
        $this->_init('SM\PWABanner\Model\Banner', 'SM\PWABanner\Model\ResourceModel\Banner');
    }
}