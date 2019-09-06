<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/5/18
 * Time: 2:02 PM
 */

namespace SM\PWABanner\Block\Adminhtml\Form\Element;


use Magento\Framework\UrlInterface;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;

class Image  extends \Magento\Framework\Data\Form\Element\Image
{

    private $urlBuilder;

    public function __construct(Factory $factoryElement,
                                CollectionFactory $factoryCollection,
                                \Magento\Framework\Escaper $escaper,
                                UrlInterface $urlBuilder,

                                array $data = [])
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $urlBuilder, $data);
    }

    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]).'/'.'banner'.$this->getValue();
        }
        return $url;
    }
}