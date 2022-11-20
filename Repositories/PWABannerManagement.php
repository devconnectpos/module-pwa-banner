<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/6/18
 * Time: 3:15 PM
 */

namespace SM\PWABanner\Repositories;


use SM\Core\Api\Data\PWABanner;
use SM\XRetail\Repositories\Contract\ServiceAbstract;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class PWABannerManagement extends ServiceAbstract
{
    /**
     * @var \SM\PWABanner\Model\ResourceModel\BannerFactory
     */
    protected $_bannerFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * PWABannerManagement constructor.
     *
     * @param \Magento\Framework\App\RequestInterface            $requestInterface
     * @param \SM\XRetail\Helper\DataConfig                      $dataConfig
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Framework\App\Filesystem\DirectoryList    $directory_list
     * @param \Magento\MediaStorage\Model\File\UploaderFactory   $uploaderFactory
     * @param \Magento\Framework\Filesystem                      $filesystem
     * @param \SM\PWABanner\Model\BannerFactory                  $bannerFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $requestInterface,
        \SM\XRetail\Helper\DataConfig $dataConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \SM\PWABanner\Model\BannerFactory $bannerFactory,
        ScopeConfigInterface $scopeConfig)
    {
        $this->fileSystem      = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->directoryList   = $directory_list;
        $this->_bannerFactory = $bannerFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($requestInterface, $dataConfig, $storeManager);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getBannerData() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $fileName = $objectManager->get('Magento\Store\Model\StoreManagerInterface')
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'banner';


        $banners      = [];
        $storeId = $this->getSearchCriteria()->getData('storeId');
        $config_active_banners = intval($this->scopeConfig->getValue("pwa/banner/pwa_banner_active", ScopeInterface::SCOPE_STORE, $storeId));
        $collection = $this->getBannerCollection($this->getSearchCriteria());
        if ($collection->getLastPageNumber() < $this->getSearchCriteria()->getData('currentPage')) {
        }
        else {
            $i = 0;
            foreach ($collection as $banner) {
                if($i < $config_active_banners) {
                    $stores = $banner->getData('store');
                    $stores = explode(',', (string)$stores);
                    if(in_array('0', $stores) || in_array($storeId, $stores)) {
                        $b = new PWABanner();
                        if (is_file(
                            $this->fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('banner') . $banner->getData('banner_url'))) {
                            $b->addData(
                                [
                                    'banner_id' => $banner->getId(),
                                    'banner_url' => $fileName . $banner->getData('banner_url'),
                                    'is_active' => $banner->getData('is_active'),
                                ]);
                            $banners[] = $b;
                            $i++;
                        }
                    }
                } else {
                    break;
                }
            }
        }

        return $this->getSearchResult()
            ->setSearchCriteria($this->getSearchCriteria())
            ->setItems($banners)
            ->setTotalCount($collection->getSize())
            ->getOutput();
    }

    public function getBannerCollection($searchCriteria) {
        /** @var \SM\PWABanner\Model\ResourceModel\Banner\Collection $collection */
        $collection = $this->_bannerFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active', \SM\PWABanner\Model\Status::STATUS_ACTIVED);
        $collection->getSelect()->order('created_at desc');
        $collection->setCurPage(is_nan((float)$searchCriteria->getData('currentPage')) ? 1 : $searchCriteria->getData('currentPage'));
        $collection->setPageSize(
            is_nan((float)$searchCriteria->getData('pageSize')) ? DataConfig::PAGE_SIZE_LOAD_CUSTOMER : $searchCriteria->getData('pageSize')
        );

        return $collection;
    }
}
