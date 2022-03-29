<?php

namespace BelVG\Showroom\Block;

use BelVG\Showroom\Model\ResourceModel\Showroom\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template;

class Index extends Template
{
    private CollectionFactory $showroomCollectionFactory;
    private Http $request;
    private ?Session $customerSession = null;
    private SessionFactory $customerSessionFactory;
    private ScopeConfigInterface $scopeConfig;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param Http $request
     * @param ScopeConfigInterface $scopeConfig
     * @param SessionFactory $customerSessionFactory
     * @param CollectionFactory $showroomCollectionFactory
     */
    public function __construct(
        Template\Context $context,
        Http $request,
        ScopeConfigInterface $scopeConfig,
        SessionFactory $customerSessionFactory,
        CollectionFactory $showroomCollectionFactory
    )
    {
        parent::__construct($context);
        $this->showroomCollectionFactory = $showroomCollectionFactory;
        $this->request = $request;
        $this->customerSessionFactory = $customerSessionFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool|null
     */
    public function isEnabled(): ?bool
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('admin/showroom/enabled', $storeScope);
    }

    /**
     * @return int|null
     */
    public function getShowroom(): ?int
    {
        return $this->request->getParam('showroom_id');
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->request->getParam('name');
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->request->getParam('email');
    }

    /**
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->request->getParam('date');
    }

    /**
     * @return array
     */
    public function getShowrooms(): array
    {
        $data = [];
        $collection = $this->showroomCollectionFactory->create();
        foreach ($collection as $item) {
            $data[] = $item->getData();
        }
        return $data;
    }

    /**
     * @return bool
     */
    public function isLogged(): bool
    {
        $this->createSession();
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @return string
     */
    public function getCustomerName(): string
    {
        $this->createSession();
        $name = '';
        try {
            $name = $this->customerSession->getCustomer()->getName();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {}
        return $name;
    }

    /**
     * @return string
     */
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
