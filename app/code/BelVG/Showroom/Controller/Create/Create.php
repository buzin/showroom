<?php

namespace BelVG\Showroom\Controller\Create;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use BelVG\Showroom\Model\ShowroomFactory as ModelFactory;
use BelVG\Showroom\Model\ResourceModel\ShowroomEntryFactory as ResourceModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Message\ManagerInterface;

class Create implements HttpPostActionInterface
{
    private RequestInterface $request;
    private ManagerInterface $messageManager;
    private ForwardFactory $forwardFactory;
    private ModelFactory $modelFactory;
    private ResourceModelFactory $resourceModelFactory;
    private RedirectFactory $redirectFactory;

    /**
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        RequestInterface $request,
        ManagerInterface $messageManager,
        ForwardFactory $forwardFactory,
        RedirectFactory $redirectFactory,
        ModelFactory $modelFactory,
        ResourceModelFactory $resourceModelFactory

    ) {
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->forwardFactory = $forwardFactory;
        $this->modelFactory = $modelFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $showroomId = $this->request->getParam('showroom_id');
        $date = $this->request->getParam('date');
        $name = $this->request->getParam('name');
        $email = $this->request->getParam('email');
        // Array to set model data or to pass values back to the form in the case of failure.
        $params = ['showroom_id' => $showroomId, 'name' => $name, 'email' => $email, 'date' => $date];

        if ($showroomId && $name && $email) {
            $model = $this->modelFactory->create();
            $resourceModel = $this->resourceModelFactory->create();
            $model->setData($params);
            try {
                $resourceModel->save($model);
                $params = [];   // do not need to pass parameters back to the form
                $this->messageManager->addSuccess(__('Showroom entry created'));
            } catch(AlreadyExistsException $e) {
                $this->messageManager->addError(__('Cannot create entry'));
            }
        } else {
            $this->messageManager->addError(__('Fill required fields'));
        }

//        $forward = $this->forwardFactory->create();
//        $forward->setController('index');
//        $forward->setParams(['name' => $name, 'email' => $email]);
//        return $forward->forward('index');

        $redirect = $this->redirectFactory->create();
        return $redirect->setPath('showroom/index/index', $params);
    }
}
