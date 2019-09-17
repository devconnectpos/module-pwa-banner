<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 6:41 PM
 */

namespace SM\PWABanner\Block\Adminhtml\Pwabanner\Edit;


class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
