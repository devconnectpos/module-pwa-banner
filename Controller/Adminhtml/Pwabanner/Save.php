<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 7:22 PM
 */

namespace SM\PWABanner\Controller\Adminhtml\Pwabanner;

use Magento\Backend\App\Action;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Save extends Action
{
    protected $scopeConfig;

    /**
     * @param Action\Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Action\Context $context, ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $model = $this->_objectManager->create('SM\PWABanner\Model\Banner');

            $id = $this->getRequest()->getParam('banner_id');
            if ($id) {
                $model->load($id);
            }

            try {
                $bannerHelper = $this->_objectManager->get('SM\PWABanner\Helper\Data');
                //if delete image
                if (isset($data['banner_url']['delete']) && $model->getBannerUrl()) {
                    $bannerHelper->removeImage($model->getBannerUrl());
                    $data['banner_url'] = '';
                }

                //upload image
                $imageFile = $bannerHelper->uploadImage('banner_url', $data);
                if ($imageFile) {
                    $data['banner_url'] = $imageFile;
                }

                $data['store'] = implode(',', $data['store']);

                //check number of active banner
                $activeBanners = $bannerHelper->numberOfActiveBanners();
                $config_active_banners = $this->scopeConfig->getValue("pwa/banner/pwa_banner_active");

                if($activeBanners >= $config_active_banners && $data['is_active'] == \SM\PWABanner\Model\Status::STATUS_ACTIVED) {
                    $data['is_active'] = \SM\PWABanner\Model\Status::STATUS_INACTIVED;
                    $this->messageManager->addWarning(__('Only %1 banners can be enabled at same time, this banner has been set to Disable.', $config_active_banners));
                }

                //save banner
                $model->setData($data);
                $model->save();
                $this->messageManager->addSuccess(__('The banner has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['banner_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
