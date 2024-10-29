<?php


namespace rednaoeasycalculationforms\pages;


use rednaoeasycalculationforms\core\PageBase;

class Help extends PageBase
{

    public function Render()
    {
        ?>

        <div style="text-align: center;margin:10px;padding: 30px;background-color: white;border: 1px solid #dfdfdf;border-radius: 20px;">
            <div style="text-align: center;">
                <img style="display: inline-block;margin-bottom: 10px" src="<?php echo esc_attr($this->Loader->URL).'images/icon.png' ?>"/>
            </div>
            <a target="_blank" style="font-size: 20px" href="https://allinoneforms.rednao.com/documentation/">You can find the documentation here</a>

            <div style="margin-top: 20px">
                <a target="_blank" style="font-size: 20px" href="https://wordpress.org/support/plugin/all-in-one-forms/">If you have any question you can get help here</a>
            </div>
        </div>
<?php
    }
}