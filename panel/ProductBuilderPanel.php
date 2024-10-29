<?php


namespace rednaoeasycalculationforms\panel;


use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\repository\ProductRepository;

class ProductBuilderPanel
{
    /** @var Loader */
    public $loader;
    public function __construct($loader)
    {
        $this->loader=$loader;
    }


    public function Execute()
    {
        global $post_id;
        $currency=get_woocommerce_price_format();
        $repository=new ProductRepository();

        $this->loader->AddRNTranslator(array('ProductFieldBuilder','InternalShared','ProductDesignerPro'));
        $this->loader->AddScript('shared-core','js/dist/SharedCore_bundle.js',array('@RNTranslator','wp-element'));
        $this->loader->AddScript('internal-shared','js/dist/InternalShared_bundle.js',array('@shared-core'));
        $this->loader->AddScript('form-builder','js/dist/FormBuilder_bundle.js',array('wp-i18n','@RNTranslator','@internal-shared'));
        $this->loader->AddStyle('form-builder','js/dist/FormBuilder_bundle.css');
        $additionalFields=array();
        $additionalFields=\apply_filters('rednao-calculated-fields-get-additional-fields',$additionalFields);
        $dependencies=array();
        foreach ($additionalFields as $field)
        {
            $dependencies[]='@'.$field;
            $this->loader->AddScript($field,'js/dist/'.$field.'_bundle.js',array('@form-builder'));
        }

        $fieldsWidthStyle=['FBDatePicker','FBSingleLabel','FBDateRange','FBFile','FBGroupPanel','FBFloatPanel','FBImagePicker','FBList','FBRepeater','FBSlider','FBButtonSelection','FBSignature','FBColorSwatcher','FBTermOfService','FBDivider','FBSurvey'];
        foreach($fieldsWidthStyle as $currentDynamicField )
        {
            $this->loader->AddStyle($currentDynamicField, 'js/dist/' . $currentDynamicField . '_bundle.css');
        }


        $dependencies=\apply_filters('woo-extra-product-load-designer',$dependencies);

        if($this->loader->IsPR())
        {
            $this->loader->AddScript('FormBuilderPr', 'js/dist/FormBuilderPr_bundle.js',array('@form-builder'));
        }


        $this->loader->AddScript('products-builder','js/dist/ProductFieldBuilder_bundle.js', array('@form-builder'));
        $this->loader->AddStyle('products-builder','js/dist/ProductFieldBuilder_bundle.css');

        if($this->loader->IsPR())
        {
            $this->loader->AddScript('multiplesteps','js/dist/MultipleSteps_bundle.js', array('@form-builder'));
            $this->loader->AddScript('multiplestepsdesigner','js/dist/MultipleStepsDesigner_bundle.js', array('@products-builder'));

            $dependencies[]='@multiplesteps';
            $dependencies[]='@multiplestepsdesigner';
        }



        $this->loader->AddScript('products-builder-runnable','js/dist/RunnableDesigner_bundle.js',\array_merge($dependencies, \array_merge($dependencies, array('@products-builder'))));
        $this->loader->AddStyle('products-builder-runnable','js/dist/RunnableDesigner_bundle.css');
        $this->loader->LocalizeScript('rednaoProductDesigner','products-builder','product_designer',array(
                'ProductId'=>$post_id,
           'ProductNonce'=>\wp_create_nonce($post_id.'_product_designer'),
           'URL'=>$this->loader->URL,
            'IsDesign'=>true,
            'IsPr'=>$this->loader->IsPR(),
            'PurchaseURL'=>'http://google.com',
            'Options'=>$repository->GetProductExtraOptions($post_id),
            'ServerOptions'=>$repository->GetAllServerOptions($post_id),
            'Variations'=>$repository->GetVariations($post_id),
            'WCCurrency'=>array(
                'Format'=>get_woocommerce_price_format(),
                'Decimals'=>wc_get_price_decimals(),
                'ThousandSeparator'=>wc_get_price_thousand_separator(),
                'DecimalSeparator'=>wc_get_price_decimal_separator(),
                'Symbol'=>get_woocommerce_currency_symbol()
            )
        ));


        ?>
        <div class="panel woocommerce_options_panel" id="rednao-advanced-products" style="padding: 10px">
            <input type="hidden" name="rednao_advanced_product_options" id="rednao_advanced_product_options_input" value=""/>
            <input type="hidden" name="rednao_advanced_product_server_options" id="rednao_advanced_product_server_options_input" value=""/>
            <div id="rednao-advanced-products-designer">
                Loading builder, please wait a bit...
            </div>
        </div>
<?php
    }
}