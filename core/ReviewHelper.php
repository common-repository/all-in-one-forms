<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 2/21/2018
 * Time: 11:55 AM
 */

namespace rednaoeasycalculationforms\core;



class ReviewHelper
{
    private $thresholds=array(15,50,200);
    private $stages=array(
        array(
            'Threshold'=>10,
            'content'=>"<p>Hello!, you have received <strong>%s submissions</strong>, that's Great!</p>
                        <p><strong>Would like to keep receiving updates and support?</strong> If so could you do me a BIG favor and give it a
                                                <a target='_blank' href='https://wordpress.org/support/plugin/all-in-one-forms/reviews/?filter=5'>5-star rating on WordPress?</a></p>
                        <p>Honestly the competition is fierce and a good review really helps the plugin thrive (<strong>that means constant updates, support and more features for you</strong>)</p> ",
            'Reviewlink'=>'Sure, keep up the good work',
            'DontShowAgain'=>'Already did or don\'t want to review (don\'t show again)'

        )/*,
        array(
            'Threshold'=>60,
            'content'=>"Hello! Its me again =), you have received <strong>%s submissions</strong>, so i was wondering, would you have time to 5 star review the plugin really quick? I know you are busy and i don't like to bother you but reviewing the plugin is really important
and ensure the continuation of thie plugin development.",
            'Reviewlink'=>'Sure, i will review the plugin really quick',
            'Remindmelink'=>'Sorry but i am not ready yet, maybe later',
            'DontShowAgain'=>'I already did'
        ),
        array(
            'Threshold'=>100,
            'content'=>"Hello! sorry to bother you again (this is the last time i do it), i just wanted to tell you that you have received <strong>%s submissions</strong> which is amazing. Could you please help me and 5-star review the plugin? it will take you less than 1 minutes and will 
greatly help me promote and keep growing this plugin that i love and i hope it has been useful for you.",
            'Reviewlink'=>'Alright, i will review the plugin quickly',
            'DontShowAgain'=>'I don\'t want to review it =('
        )*/
    );
    private $stageProperty='aio_review_stage';
    private $currentStage;
    private $count=0;
    public function Start()
    {
        $this->currentStage=$this->GetCurrentStage();
        if($this->currentStage==null)
            return;

        if(!$this->ShouldPrintNotice())
            return;

        $this->PrintNotice();

    }

    private function GetCurrentStage()
    {
        $stageNumber= get_option($this->stageProperty,0);
        if($stageNumber>=count($this->stages))
            return null;

        return $this->stages[$stageNumber];
    }

    private function ShouldPrintNotice()
    {
        global $wpdb;
        $this->count=$wpdb->get_var("SELECT COUNT(*) FROM ".AllInOneForms()->GetLoader()->RECORDS_TABLE);
        return $this->count>=$this->currentStage['Threshold'];
    }

    private function GetContent()
    {
        return sprintf($this->currentStage['content'],$this->count);


    }

    private function PrintNotice()
    {
        ?>
        <style type="text/css">
            .sfReviewButton{
                display: inline-block;
                padding: 6px 12px;
                margin-bottom: 0;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.42857143;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                -ms-touch-action: manipulation;
                touch-action: manipulation;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                background-image: none;
                border: 1px solid transparent;
                border-radius: 4px;
                color: #fff;
                background-color: #5bc0de;
                border-color: #46b8da;
                text-decoration: none;
            }

            .sfReviewButton:hover{
                color: #fff;
                background-color: #31b0d5;
                border-color: #269abc;
            }
        </style>
        <div class="notice is-dismissible notice-info sfReviewNotice" style="clear:both; padding-bottom:0;">
            <div style="padding-top: 5px;">


                <table >
                    <tbody  style="width:calc(100% - 135px);">
                    <tr>
                        <td>
                            <img style="display: inline-block;width:128px;vertical-align: top;" src="<?php echo AllInOneForms()->GetLoader()->URL?>images/icon.png">
                        </td>
                        <td>
                            <div style="display: inline-block; vertical-align: top;margin-left: 5px;"><span style="font-size: 16px;font-family: Verdana"><div style="padding-bottom: 1px;margin-bottom: 0;"><?php echo $this->GetContent()?></div>
                                            <p style="font-size: 13px;padding-top:0;margin-top:0;font-style: italic;">- Thank you, Edgar Rojas</p>
                                            <ul style="list-style: circle;margin-left: 30px;">
                                                <li><a target="_blank" style="display: block" href="https://wordpress.org/support/plugin/all-in-one-forms/reviews/?filter=5"><?php echo $this->currentStage['Reviewlink']?></a></li>
                                                <?php if(isset($this->currentStage['Remindmelink'])){?>
                                                    <li><a id="aioml" style="display: block" href="https://wordpress.org/support/plugin/all-in-one-forms/reviews/?filter=5"><?php echo $this->currentStage['Remindmelink']?></a></li>
                                                <?php } ?>
                                                <li><a id="aiosa" style="display: block" href="https://wordpress.org/support/plugin/all-in-one-forms/reviews/?filter=5"><?php echo $this->currentStage['DontShowAgain']?></a></li>
                                            </ul>
                            </div>
                        </td>

                    </tr>

                    </tbody>
                </table>
                <div style="clear: both;"></div>
            </div>

        </div>

        <script type="text/javascript">
            jQuery(document).ready( function($) {
                jQuery('#aioml').click(function(e){
                    e.preventDefault();
                    $.post( ajaxurl, {
                        action: 'aio_remind_me'
                    });
                    jQuery('.sfReviewNotice').remove();
                });

                jQuery('#aiosa').click(function(e){
                    e.preventDefault();
                    $.post( ajaxurl, {
                        action: 'aio_dontshowagain'
                    });
                    jQuery('.sfReviewNotice').remove();
                });
            });
        </script> <?php
    }
}