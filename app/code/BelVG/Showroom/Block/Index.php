<?php

namespace BelVG\Showroom\Block;

use BelVG\Showroom\Model\ShowroomFactory;
use Magento\Customer\Model\Session;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template;

class Index extends \Magento\Framework\View\Element\Template
{
    private ShowroomFactory $showroomFactory;
    private Http $request;
    private Session $customerSession;
    private AuthSession $authSession;

    public function __construct(Template\Context $context,
                                Http $request,
                                Session $customerSession,
                                AuthSession $authSession,
                                ShowroomFactory $showroomFactory,
                                array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->showroomFactory = $showroomFactory;
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->authSession = $authSession;
    }

    public function getShowroom()
    {
        return $this->request->getParam('showroom_id');
    }

    public function getName()
    {
        $name = $this->request->getParam('name');
        if (!$name) {
            $name = $this->customerSession->getCustomer()->getName();
        }
        if (!$name) {
            $name = $this->authSession->getUser()->getName();
        }
        return $name;
    }

    public function getEmail()
    {
        $email = $this->request->getParam('email');
        if (!$email) {
            $email = $this->customerSession->getCustomer()->getEmail();
        }
        return $email;
    }

    public function getDate()
    {
        return $this->request->getParam('date');
    }

    public function getShowrooms(): array
    {
        $data = [];
        $showroom = $this->showroomFactory->create();
        $collection = $showroom->getCollection();
        foreach($collection as $item) {
            $data[] = $item->getData();
        }
        return $data;
    }

}
