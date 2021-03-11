<?php

namespace BelVG\Showroom\Controller\Create;

use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use BelVG\Showroom\Model\ShowroomEntryFactory as ModelFactory;
use BelVG\Showroom\Model\ResourceModel\ShowroomEntryFactory as ResourceModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\Event\ManagerInterface as EventManager;

class Create implements HttpPostActionInterface
{
    private RequestInterface $request;
    private SessionFactory $customerSessionFactory;
    private RedirectFactory $redirectFactory;
    private MessageManager $messageManager;
    private EventManager $eventManager;
    private ModelFactory $modelFactory;
    private ResourceModelFactory $resourceModelFactory;

    public function __construct(
        RequestInterface $request,
        SessionFactory $customerSessionFactory,
        RedirectFactory $redirectFactory,
        MessageManager $messageManager,
        EventManager $eventManager,
        ModelFactory $modelFactory,
        ResourceModelFactory $resourceModelFactory

    ) {
        $this->request = $request;
        $this->customerSessionFactory = $customerSessionFactory;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->eventManager = $eventManager;
        $this->modelFactory = $modelFactory;
        $this->resourceModelFactory = $resourceModelFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $showroomId = $this->request->getParam('showroom_id');
        $date = $this->request->getParam('date');

        $customerSession = $this->customerSessionFactory->create();
        if ($customerSession->isLoggedIn()) {
            $name = $customerSession->getCustomer()->getName();
            $email = $customerSession->getCustomer()->getEmail();
        } else {
            $name = $this->request->getParam('name');
            $email = $this->request->getParam('email');
        }
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        // Array to save model data and
        // to dispatch new entry event and
        // to pass values back to the form in the case of failure.
        $params = ['showroom_id' => $showroomId, 'name' => $name, 'email' => $email, 'date' => $date];

        if ($showroomId && $name && $email) {
            $model = $this->modelFactory->create();
            $resourceModel = $this->resourceModelFactory->create();
            $model->setData($params);
            try {
                $resourceModel->save($model);
                $this->eventManager->dispatch('belvg_showroom_new_entry', $params);
                $this->messageManager->addSuccess(__('Showroom entry created'));
                $params = [];   // do not need to pass parameters back to the form
            } catch(AlreadyExistsException $e) {
                $this->messageManager->addError(__('Entry already exists'));
            }
            catch (\Exception $e) {
                $this->messageManager->addError(__('Cannot create entry'));
            }
        } else {
            $this->messageManager->addError(__('Fill required fields'));
        }

        $redirect = $this->redirectFactory->create();
        return $redirect->setPath('showroom/index/index', $params);
    }
}
