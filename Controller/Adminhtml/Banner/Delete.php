<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 8:02 PM
 */

namespace SM\PWABanner\Controller\Adminhtml\Banner;


class Delete extends \Magento\Backend\App\Action
{
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('banner_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('SM\PWABanner\Model\Banner');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccess(__('The banner has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['banner_id' => $id]);
            }
        } else {
            $this->messageManager->addError(__('We can\'t find the banner to delete.'));
            return $resultRedirect->setPath('*/*/');
        }
    }
}
