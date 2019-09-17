<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 4:16 PM
 */

namespace SM\PWABanner\Block\Adminhtml;


class Pwabanner extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'SM_PWABanner';
        $this->_headerText = __('Manage PWA Banners');
        $this->_addButtonLabel = __('Add New Banner');
        parent::_construct();
    }
}
