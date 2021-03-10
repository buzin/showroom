<?php

namespace BelVG\Showroom\Block;

use BelVG\Showroom\Model\ShowroomFactory;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template;

class Index extends Template
{
    private ShowroomFactory $showroomFactory;
    private Http $request;
    private ?Session $customerSession = null;
    private SessionFactory $customerSessionFactory;
    private ScopeConfigInterface $scopeConfig;

    public function __construct(Template\Context $context,
                                Http $request,
                                ScopeConfigInterface $scopeConfig,
                                SessionFactory $customerSessionFactory,
                                ShowroomFactory $showroomFactory
    )
    {
        parent::__construct($context);
        $this->showroomFactory = $showroomFactory;
        $this->request = $request;
        $this->customerSessionFactory = $customerSessionFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('admin/showroom/enabled', $storeScope);
    }

    public function getShowroom(): ?int
    {
        return $this->request->getParam('showroom_id');
    }

    public function getName(): ?string
    {
        return $this->request->getParam('name');
    }

    public function getEmail(): ?string
    {
        return $this->request->getParam('email');
    }

    public function getDate(): ?string
    {
        return $this->request->getParam('date');
    }

    public function getShowrooms(): array
    {
        $data = [];
        $showroom = $this->showroomFactory->create();
        $collection = $showroom->getCollection();
        foreach ($collection as $item) {
            $data[] = $item->getData();
        }
        return $data;
    }

    public function isLogged(): bool
    {
        $this->createSession();
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomerName(): string
    {
        $this->createSession();
        return $this->customerSession->getCustomer()->getName();
    }

    public function getCustomerEmail(): string
    {
        $this->createSession();
        return $this->customerSession->getCustomer()->getEmail();
    }

    /**
     * Fetching the logged status from Session model will not work in case you want to use it after enabling Magento
     * default FPC cache, in that case, you should use SessionFactory instead.
     */
    private function createSession(): void
    {
        if (!$this->customerSession) {
            $this->customerSession = $this->customerSessionFactory->create();
        }
    }

}
