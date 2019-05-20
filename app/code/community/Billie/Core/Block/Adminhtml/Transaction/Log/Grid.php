<?php

class Billie_Core_Block_Adminhtml_Transaction_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_id');
         $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {

        $collection = Mage::getModel('billie_core/transaction_log')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('entity_id',
            array(
                'header' => $this->__('id'),
                'width' => '10px',
                'index' => 'entity_id'
            )
        );
        $this->addColumn('store_id',
            array(
                'header' => $this->__('Storeview'),
                'width' => '10px',
                'index' => 'store_id'
            )
        );
        $this->addColumn('order_id',
            array(
                'header' => $this->__('order_id'),
                'width' => '10px',
                'index' => 'order_id'
            )
        );
        $this->addColumn('reference_id',
            array(
                'header' => $this->__('reference_id'),
                'width' => '10px',
                'index' => 'reference_id'
            )
        );
        $this->addColumn('mode',
            array(
                'header' => $this->__('mode'),
                'width' => '10px',
                'index' => 'mode'
            )
        );

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));

        $this->addExportType('*/*/exportExcel', $this->__('Excel XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }


}
