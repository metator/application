<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
        $products = $this->productMapper()->find();
        return array(
            'products'=>$products
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
            $image_hash_to_add = false;
            $file = $_FILES['image_to_add']['tmp_name'];

            if($file) {
                $saver = new Saver(file_get_contents($file));
                $saver->save();
                $image_hash_to_add = $saver->getHash();
            }

            $product->setSku($form->getValue('sku'));
            $product->setName($form->getValue('name'));
            $product->setDescription($form->getValue('description'));
            $product->setBasePrice($form->getValue('basePrice'));
            $product->setCategories($form->getValue('categories'));
            $product->setDefaultImageHash($this->params()->fromPost('default_image'));

            if($image_hash_to_add) {
                $product->addImageHash($image_hash_to_add);
            }

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

    /** @return \Metator\Product\DataMapper */
    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Product\DataMapper');
        }
        return $this->productMapper;
    }

    /** @return \Application\CategoryMapper */
    function categoryMapper()
    {
        if (!$this->categoryMapper) {
            $sm = $this->getServiceLocator();
            $this->categoryMapper = $sm->get('Category\DataMapper');
        }
        return $this->categoryMapper;
    }
}