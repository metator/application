<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Product\Controller;

use Application\AbstractActionController;
use Metator\Product\Form;
use Metator\Product\Product;
use Metator\Image\Saver;

class ProductController extends AbstractActionController
{
    protected $productMapper, $categoryMapper;

    function viewAction()
    {
        $product = $this->productMapper()->load($this->params('id'));
        return array('product'=>$product);
    }

    function manageAction()
    {
        $page = $this->params()->fromQuery('page',1);
        $perpage = 6;
        $offset = ($page * $perpage)-$perpage;

        $products = $this->productMapper()->find(array(), $offset, $perpage);
        $productCount = $this->productMapper()->count();

        $pageAdapter = new \Zend\Paginator\Adapter\Null($productCount);
        $paginator = new \Zend\Paginator\Paginator($pageAdapter);
        $paginator->setItemCountPerPage($perpage);
        $paginator->setCurrentPageNumber($page);

        if($offset+$perpage > $productCount) {
            $end = $productCount;
        } else {
            $end = $offset+$perpage;
        }

        return array(
            'start'=>$offset+1,
            'end'=>$end,
            'total'=>$productCount,
            'paginator'=>$paginator,
            'products'=>$products,
        );
    }

    function deactivateAction()
    {
        $id = $this->params('id');
        $this->productMapper()->deactivate($id);
        return $this->redirect()->toRoute('product_manage');
    }

    function editAction()
    {
        $form = new Form($this->categoryMapper());

        if($this->params('id')) {
            $product = $this->productMapper()->load($this->params('id'));
            $form->populate(array(
                'name'=>$product->getName(),
                'description'=>$product->getDescription(),
                'sku'=>$product->getSku(),
                'basePrice'=>$product->getBasePrice(),
                'categories'=>$product->getCategories(),
            ));
        } else {
            $product = new Product;
        }

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {
            //print_r($_POST);exit;
            /**
             * Save the uploaded image ... [if there is one]
             */
            $image_hash_to_add = false;
            $file = $_FILES['image_to_add']['tmp_name'];
            if($file) {
                $saver = new Saver(file_get_contents($file));
                $saver->save();
                $image_hash_to_add = $saver->getHash();
            }

            /**
             * Set scalar properties & default image
             */
            $product->setSku($form->getValue('sku'));
            $product->setName($form->getValue('name'));
            $product->setDescription($form->getValue('description'));
            $product->setBasePrice($form->getValue('basePrice'));
            $product->setCategories($form->getValue('categories'));
            $product->setDefaultImageHash($this->params()->fromPost('default_image'));

            /**
             * Add new image ... [if one was uploaded]
             */
            if($image_hash_to_add) {
                $product->addImageHash($image_hash_to_add);
            }

            /**
             * Update existing attributes
             */
            foreach($product->attributes() as $attribute=>$existingValue) {
                $newValue = $this->params()->fromPost("attribute_$attribute");
                $product->setAttributeValue($attribute, $newValue);
            }

            if($this->params()->fromPost('new_attribute_label') && $this->params()->fromPost('new_attribute_value')) {
                $product->setAttributeValue($this->params()->fromPost('new_attribute_label'), $this->params()->fromPost('new_attribute_value'));
            }

            /**
             * Save & redirect
             */
            $this->productMapper()->save($product);
            if(!$this->params()->fromPost('save_and_continue')) {
                return $this->redirect()->toRoute('product_manage');
            }
        }

        return array(
            'form'=>$form,
            'product'=>$product
        );
    }
}