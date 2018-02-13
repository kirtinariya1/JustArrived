<?php
namespace Ktpl\JustArrived\Block;

class JustArrived extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_productRepository;
    protected $_productImageHelper;
    protected $_cartHelper;        
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,      
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
    //    \Magento\Catalog\Helper\Image $productImageHelper,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Block\Product\Context $contextproduct,
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productRepository = $productRepository;
    //    $this->_productImageHelper = $productImageHelper;
        $this->_productImageHelper = $contextproduct->getImageHelper();
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->_cartHelper = $contextproduct->getCartHelper();    
        parent::__construct($context, $data);
    }
    public function getNewProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToSort('created_at', 'desc');       
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $collection->setPageSize(5); 
        return $collection;
    }
    public function resizeImage($product, $imageId, $width, $height)
    {
        $resizedImage = $this->_productImageHelper->init($product, $imageId)
                               ->constrainOnly(TRUE)
                               ->keepAspectRatio(TRUE)
                               ->keepTransparency(TRUE)
                               ->keepFrame(FALSE)
                               ->resize($width, $height);
        return $resizedImage;
    }
    public function getAddToCartUrl($product, $additional = [])
    {
        return $this->_cartHelper->getAddUrl($product, $additional);
    }
}

