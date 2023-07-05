<?php
/**
 * @category    Sigma
 * @package     Sigma_DuskWebApisLog
 * @copyright   Copyright (c) Sigma Infosolutions (https://www.sigmainfo.net/)
 * @license     https://www.sigmainfo.net/LICENSE.txt
 */
namespace Sigma\DuskWebApisLog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddDuskLogs implements ObserverInterface
{
    /**
     * @var \Sigma\DuskWebApisLog\Model\DuskApiLogsFactory
     */
    private $duskApiLogFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Class Constructor
     */
    public function __construct(
        \Sigma\DuskWebApisLog\Model\DuskApiLogsFactory $duskApiLogFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime
    ) {
        $this->duskApiLogFactory = $duskApiLogFactory;
        $this->dateTime          = $dateTime;
    }

    public function execute(Observer $observer)
    {
        $data = [];
        $duskApiLogModel = $this->duskApiLogFactory->create();
        $recordData = $observer->getEvent()->getRecord();

        $data['status_code'] = $recordData['status_code'];
        $data['request_url'] = $recordData['api_endpoint'];
        $data['request_body'] = $recordData['request_body'];
        $data['response_body'] = $recordData['response_body'];
        $data['created_at'] = $this->dateTime->formatDate(true);

        $duskApiLogModel->setData($data);
        $duskApiLogModel->save();
        return $this;
    }
}