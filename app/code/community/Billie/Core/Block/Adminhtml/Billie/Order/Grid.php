<?php

class Billie_Core_Block_Adminhtml_Billie_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('billie_core_billie_order_id');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());

        $collection->getSelect()->join( array('order'=>'sales_flat_order'), '`main_table`.entity_id = order.entity_id', array('order.billie_reference_id'))
            ->where("NULLIF(order.billie_reference_id, '') IS NOT NULL");
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Payone_Core_Block_Adminhtml_Sales_Order_Grid
     */
    public function _prepareColumns()
    {

        $this->addColumn(
            'real_order_id', array(
                'header'=> Mage::helper('sales')->__('Order #'),
                'width' => '80px',
                'type'  => 'text',
                'index' => 'increment_id',
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'store_id', array(
                    'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                    'index'     => 'store_id',
                    'type'      => 'store',
                    'store_view'=> true,
                    'display_deleted' => true,
                )
            );
        }

        $this->addColumn(
            'created_at', array(
                'header' => Mage::helper('sales')->__('Purchased On'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '100px',
            )
        );

        $this->addColumn(
            'billing_name', array(
                'header' => Mage::helper('sales')->__('Bill to Name'),
                'index' => 'billing_name',
            )
        );

        $this->addColumn(
            'company',
            array(
                'header' => $this->__('Company'),
                'index' => 'entity_id',
                'renderer'  => 'Billie_Core_Block_Adminhtml_Billie_Order_Renderer_Company',
            )
        );

        $this->addColumn(
            'billie_legal_form',
            array(
                'header' => $this->__('Legal Form'),
                'index' => 'entity_id',
                'renderer'  => 'Billie_Core_Block_Adminhtml_Billie_Order_Renderer_LegalForm',
            )
        );

        $this->addColumn(
            'billie_reference_id', array(
                'header' => Mage::helper('sales')->__('Billie Reference Id'),
                'index' => 'billie_reference_id',
            )
        );

        $this->addColumn(
            'base_grand_total', array(
                'header' => Mage::helper('sales')->__('G.T. (Base)'),
                'index' => 'base_grand_total',
                'type'  => 'currency',
                'currency' => 'base_currency_code',
            )
        );

        $this->addColumn(
            'grand_total', array(
                'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
                'index' => 'grand_total',
                'type'  => 'currency',
                'currency' => 'order_currency_code',
            )
        );

        $this->addColumn(
            'status', array(
                'header' => Mage::helper('sales')->__('Status'),
                'index' => 'status',
                'type'  => 'options',
                'width' => '70px',
                'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
            )
        );

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn(
                'action',
                array(
                    'header' => Mage::helper('sales')->__('Action'),
                    'width' => '50px',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),
                            'url' => array('base' => 'adminhtml/sales_order/view'),
                            'field' => 'order_id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
                )
            );
        }

        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');


        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * @return Payone_Core_Block_Adminhtml_Sales_Order_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem(
                'cancel_order', array(
                    'label'=> Mage::helper('sales')->__('Cancel'),
                    'url'  => $this->getUrl('*/*/massCancel'),
                )
            );
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem(
                'hold_order', array(
                    'label'=> Mage::helper('sales')->__('Hold'),
                    'url'  => $this->getUrl('*/*/massHold'),
                )
            );
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem(
                'unhold_order', array(
                    'label'=> Mage::helper('sales')->__('Unhold'),
                    'url'  => $this->getUrl('*/*/massUnhold'),
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'pdfinvoices_order', array(
                'label'=> Mage::helper('sales')->__('Print Invoices'),
                'url'  => $this->getUrl('*/*/pdfinvoices'),
            )
        );

        $this->getMassactionBlock()->addItem(
            'pdfshipments_order', array(
                'label'=> Mage::helper('sales')->__('Print Packingslips'),
                'url'  => $this->getUrl('*/*/pdfshipments'),
            )
        );

        $this->getMassactionBlock()->addItem(
            'pdfcreditmemos_order', array(
                'label'=> Mage::helper('sales')->__('Print Credit Memos'),
                'url'  => $this->getUrl('*/*/pdfcreditmemos'),
            )
        );

        $this->getMassactionBlock()->addItem(
            'pdfdocs_order', array(
                'label'=> Mage::helper('sales')->__('Print All'),
                'url'  => $this->getUrl('*/*/pdfdocs'),
            )
        );

        $this->getMassactionBlock()->addItem(
            'print_shipping_label', array(
                'label'=> Mage::helper('sales')->__('Print Shipping Labels'),
                'url'  => $this->getUrl('adminhtml/sales_order_shipment/massPrintShippingLabel'),
            )
        );

        return $this;
    }


    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * @param $row
     * @return bool|string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getId()));
    }


}


