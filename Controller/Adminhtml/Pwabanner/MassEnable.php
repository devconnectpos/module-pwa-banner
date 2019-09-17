<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 8:00 PM
 */

namespace SM\PWABanner\Controller\Adminhtml\Pwabanner;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassEnable
 */
class MassEnable extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var \SM\PWABanner\Model\ResourceModel\Banner\CollectionFactory
     */
    protected $bannerFactory;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \SM\PWABanner\Helper\Data
     */
    private $helper;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param \SM\PWABanner\Model\ResourceModel\Banner\CollectionFactory $bannerFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param \SM\PWABanner\Helper\Data $helper
     */
    public function __construct(Context $context,
                                Filter $filter,
                                \SM\PWABanner\Model\ResourceModel\Banner\CollectionFactory $bannerFactory,
                                ScopeConfigInterface $scopeConfig,
                                \SM\PWABanner\Helper\Data $helper)
    {
        $this->filter = $filter;
        $this->bannerFactory = $bannerFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
        $this->helper = $helper;
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->bannerFactory->create());
            $i = 0;
            foreach ($collection as $item) {
                $item->setIsActive(true);
                $item->save();
                $i++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been enabled.', $i));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
