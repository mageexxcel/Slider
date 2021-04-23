<?php


namespace Adtones\Stock\Ui\Component\Listing\Column;

class OrderStockActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    const URL_PATH_ALLOCATE = 'adtones_stock/orderstock/allocate';
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\Request\Http $request,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->_request = $request;
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
                if (isset($item['stock_id'])) {

                    $item['order_id'] = $this->_request->getParam("orderId");
                    $item[$this->getData('name')] = [
                        'allocate' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_ALLOCATE,
                                [
                                    'stock_id' => $item['stock_id'],
                                    // 'edition_id' => $item['edition_id'],
                                ]
                            ),
                            'label' => __('Allocate'),
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
