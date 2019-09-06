<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 7:15 PM
 */

namespace SM\PWABanner\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use SM\PWABanner\Model\BannerFactory;

class Data {
    const MEDIA_PATH = 'banner';

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\HTTP\Adapter\FileTransferFactory
     */
    protected $httpFactory;

    /**
     * File Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * File Uploader factory
     *
     * @var File
     */
    protected $_ioFile;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    private $_imageFactory;

    protected $_bannerFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param File $ioFile
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Image\Factory $imageFactory
     * @param BannerFactory $bannerFactory
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        File $ioFile,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Image\Factory $imageFactory,
        BannerFactory $bannerFactory
    )
    {
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->httpFactory = $httpFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_storeManager = $storeManager;
        $this->_imageFactory = $imageFactory;
        $this->_bannerFactory = $bannerFactory;
    }

    /**
     * Remove news item image by image filename
     *
     * @param string $imageFile
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function removeImage($imageFile)
    {
        $io = $this->_ioFile;
        $io->open(array('path' => $this->getBaseDir()));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
    }

    /**
     * Upload image and return uploaded image file name or false
     *
     * @param $input
     * @param $data
     * @return bool|string
     */
    public function uploadImage($input, $data)
    {
        try {

            $uploader = $this->_fileUploaderFactory->create(['fileId' => $input]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);
            $result = $uploader->save($this->getBaseDir());
            return $result['file'];

        } catch (\Exception $e) {
            if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                throw new FrameworkException($e->getMessage());
            } else {
                if (isset($data[$input]['value'])) {
                    return $data[$input]['value'];
                }
            }
        }
        return '';
    }

    /**
     * Return the base media directory for News Item images
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getBaseDir()
    {
        return $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath(self::MEDIA_PATH);

    }

    /**
     * Return the Base URL for News Item images
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]).'/'.self::MEDIA_PATH;
    }

    public function numberOfActiveBanners() {
        $collection = $this->_bannerFactory->create()->getCollection()
            ->addFieldToFilter('is_active', \SM\PWABanner\Model\Status::STATUS_ACTIVED);
        return $collection->getSize();
    }
}