<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 8:02 PM
 */

namespace SM\PWABanner\Controller\Adminhtml\Pwabanner;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassEnable
 */
class MassDisable extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var bannerFactory
     */
    protected $bannerFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param \SM\PWABanner\Model\ResourceModel\Banner\CollectionFactory $bannerFactory
     */
    public function __construct(Context $context, Filter $filter, \SM\PWABanner\Model\ResourceModel\Banner\CollectionFactory $bannerFactory)
    {
        $this->filter = $filter;
        $this->bannerFactory = $bannerFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->bannerFactory->create());

        foreach ($collection as $item) {
            $item->setIsActive(false);
            $item->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been disabled.', $collection->getSize()));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
