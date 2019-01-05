<?php
/**
 *@copyright : QTeqLab
 *@author	 : Vishal Sinha < vishalsinhadev@gmail.com >
 */
namespace app\modules\blog\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class NextPrevious extends Widget
{

    public $model;

    public $prevLinkClass = "prev-post text-left d-flex align-items-center";

    public $nextLinkClass = "next-post text-right d-flex align-items-center justify-content-end";

    public $prevLabel = "Previous Post";

    public $nextLabel = "Next Post";

    public $prevIcon = 'fa fa-angle-left';

    public $nextIcon = 'fa fa-angle-right';

    public $prevUrl;

    public $nextUrl;

    public $url = 'guest-view';

    const PREV = 0;

    const NEXT = 1;

    public function init()
    {
        $id = $this->getNextOrPrevId($this->model->id, 'prev');
        if ($id != null)
            $this->prevUrl = Url::toRoute([
                $this->url,
                'id' => $id
            ]);
        $nid = $this->getNextOrPrevId($this->model->id, 'next');
        if ($nid != null)
            $this->nextUrl = Url::toRoute([
                $this->url,
                'id' => $nid
            ]);

        parent::init();
    }

    public function run()
    {
        echo Html::tag('div', Html::a($this->getPrev(), $this->prevUrl, [
            'class' => $this->prevLinkClass
        ]) . Html::a($this->getNext(), $this->nextUrl, [
            'class' => $this->nextLinkClass
        ]), [
            'class' => 'posts-nav d-flex justify-content-between align-items-stretch flex-column flex-md-row'
        ]);
    }

    function getPrev()
    {
        return Html::tag('div', Html::tag('i', '', [
            'class' => $this->prevIcon
        ]), [
            'class' => 'icon prev'
        ]) . Html::tag('div', Html::tag('strong', $this->prevLabel, [
            'class' => 'text-primary'
        ]) . Html::tag('h6', 'I Bought a Wedding Dress'), [
            'class' => 'text'
        ]);
    }

    function getNext()
    {
        return Html::tag('div', Html::tag('strong', $this->nextLabel, [
            'class' => 'text-primary'
        ]) . Html::tag('h6', 'I Bought a Wedding Dress'), [
            'class' => 'text'
        ]) . Html::tag('div', Html::tag('i', '', [
            'class' => $this->nextIcon
        ]), [
            'class' => 'icon next'
        ]);
    }

    function getNextOrPrevId($currentId, $nextOrPrev)
    {
        $records = NULL;
        if ($nextOrPrev == "prev") {
            $where = [
                '<',
                'id',
                $this->model->id
            ];
            $order = [
                'id' => SORT_DESC
            ];
        }
        if ($nextOrPrev == "next") {
            $where = [
                '>',
                'id',
                $this->model->id
            ];
            $order = [
                'id' => SORT_ASC
            ];
        }
        $records = get_class($this->model)::find()->select([
            'id'
        ])
            ->where($where)
            ->orderBy($order)
            ->one();
        if (! empty($records)) {
            return $records->id;
        }
        return NULL;
    }
}
