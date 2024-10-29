<?php


namespace rednaoeasycalculationforms\blocks;


use rednaoeasycalculationforms\ajax\FormListAjax;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\pages\FormList;

class BlockLoader
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
        add_action( 'init',array($this,'CreateBlock'));
        add_action( 'enqueue_block_editor_assets', array($this,'EnqueueBlock'));

    }

    public function EnqueueBlock(){
        /*$this->Loader->AddScript('createforms-block-editor','js/dist/FreeBlocks_bundle.js',array(
            'wp-blocks',
            'wp-i18n',
            'wp-element',
        ));

        $formList=new FormListAjax($this->Loader, true);


        $this->Loader->LocalizeScript('RedNaoEasyCalculationFormsBlocks','createforms-block-editor','RedNaoEasyCalculationFormsBlocks',array(
            'FormList'=>$formList->ListForm('form_name', 20, 0,'asc','')['Result'],
            'URL'=>$this->Loader->URL
        ));*/
    }

    public function CreateBlock(){
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }
        $dir = dirname( __FILE__ );


        register_block_type( 'easy-calculation-forms/createforms', array(
            'editor_script' => 'createforms-block-editor',
            'editor_style'  => 'createforms-block-editor',
            'style'         => 'createforms-block',
            'attributes'      => array(
                'formId'       => array(
                    'type' => 'string',
                )
            ),
            'render_callback' => array($this,'RenderForm')
        ) );
    }

    public function RenderForm($value){
        if(isset($value['formId']))
        {
            return \do_shortcode('[rnform]'.$value['formId'].'[/rnform]');
        }

        return '';
    }

}