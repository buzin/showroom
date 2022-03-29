<?php

namespace BelVG\Showroom\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class NewEntryNotifier implements ObserverInterface
{
    const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';

    private TransportBuilder $transportBuilder;
    private StateInterface $inlineTranslation;
    private ScopeConfigInterface $scopeConfig;

    /**
     * NewEntryNotifier constructor.
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig)
    {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($showroomId = $observer->getData('showroom_id')) {

            $this->inlineTranslation->suspend();

            try {
                $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($observer->getData());

                // Send email notification to admin.
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $transport = $this->transportBuilder
                    ->setTemplateIdentifier('new_entry_email') // this code we have mentioned in the email_templates.xml
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars(['entry' => $postObject])
                    ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                    ->getTransport();

                $transport->sendMessage();

            } catch (\Exception $e) {}

            $this->inlineTranslation->resume();
        }
    }
}
