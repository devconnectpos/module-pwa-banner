<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 3:38 PM
 */

namespace SM\PWABanner\Model;


use SM\PWABanner\Api\Data\BannerInterface;
use \Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\Context;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class Banner extends \Magento\Framework\Model\AbstractModel implements BannerInterface, IdentityInterface
{

    const CACHE_TAG = 'sm_pwa_banner';

    private $dateFactory;

    /**
     * Banner constructor.
     * @param Context $context
     * @param DateTimeFactory $dateFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        DateTimeFactory $dateFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dateFactory = $dateFactory;
    }

    public function _construct()
    {
        $this->_init('SM\PWABanner\Model\ResourceModel\Banner');
    }

    /**
     * @return string
     */
    public function getBannerUrl()
    {
        return $this->getData(self::BANNER_URL);
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setBannerUrl($url)
    {
        return $this->setData(self::BANNER_URL, $url);
    }

    /**
     * @param int $is_active
     * @return $this
     */
    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

    /**
     * @param string $date
     * @return $this
     */
    public function setCreatedAt($date)
    {
        return $this->setData(self::CREATED_AT, $date);
    }

    /**
     * @param string $date
     * @return $this
     */
    public function setUpdatedAt($date)
    {
        return $this->setData(self::UPDATED_AT, $date);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->getData(self::BANNER_ID);
    }

    /**
     * @param int
     * @return $this
     */
    public function setId($id) {
        return $this->setData(self::BANNER_ID, $id);
    }

    public function beforeSave() {
        if (! $this->getId()) {
            $this->setCreatedAt($this->dateFactory->create()->gmtDate());
        }

        $this->setUpdatedAt($this->dateFactory->create()->gmtDate());

        return parent::beforeSave();
    }
}