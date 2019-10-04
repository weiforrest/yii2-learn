<?php
/**
 * 用于注册JS内容，不需要添加<script>标签，直接在JsBlock中写JS脚本即可
 * example:
 * JsBlock::begin();
 * arert("JsBlock has worked")
 * JsBlock::end();
 */
namespace common\widgets;

use yii\base\Widget;
use yii\web\View;

class JsBlock extends Widget
{
    public $key = null;

    public $pos = View::POS_END;
    /**
     * Starts recording a block.
     */
    public function init()
    {
        parent::init();
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     */
    public function run()
    {
        $block = ob_get_clean();
        $block = trim($block);
        $this->view->registerJs($block,$this->pos,$this->key);
    }
}