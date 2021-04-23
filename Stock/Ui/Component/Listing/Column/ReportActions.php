<?php


namespace Adtones\Stock\Ui\Component\Listing\Column;

class ReportActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    const URL_ORDER_VIEW = 'sales/order/view/order_id/';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_customHelper;
    
    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Adtones\Stock\Helper\Data $customHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Adtones\Stock\Helper\Data $customHelper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->_customHelper = $customHelper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $orderId = $this->_customHelper->getOrderNormalId($item['order_id']);
                if (isset($item['report_id'])) {
                    $item[$this->getData('name')] = [
                        'view' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_ORDER_VIEW,
                                [
                                    'report_id' => $item['report_id'],
                                    'order_id' => $orderId,
                                ]
                            ),
                            'label' => __('View Order')
                        ]
                    ];
                }
            }
        }
        
        return $dataSource;
    }
}
