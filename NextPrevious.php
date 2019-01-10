<?php
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

    public $prevTitle;

    public $nextTitle;

    public $nresult;

    public $presult;

    public $url = 'guest-view';

    const PREV = 0;

    const NEXT = 1;

    public function init()
    {
        $this->presult = $this->getNextOrPrevId(self::PREV);
        if ($this->presult != null) {
            $this->prevUrl = $this->presult->getUrl($this->url);
            $this->prevTitle = $this->presult->title;
        }
        $this->nresult = $this->getNextOrPrevId(self::NEXT);
        if ($this->nresult != null) {
            $this->nextUrl = $this->nresult->getUrl($this->url);
            $this->nextTitle = $this->nresult->title;
        }

        parent::init();
    }

    public function run()
    {
        $link = '';
        if ($this->presult != null) {
            $link .= Html::a($this->getPrev(), $this->prevUrl, [
                'class' => $this->prevLinkClass
            ]);
        }
        if ($this->nresult != null) {
            $link .= Html::a($this->getNext(), $this->nextUrl, [
                'class' => $this->nextLinkClass
            ]);
        }

        echo Html::tag('div', $link, [
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
        ]) . Html::tag('h6', $this->prevTitle), [
            'class' => 'text'
        ]);
    }

    function getNext()
    {
        return Html::tag('div', Html::tag('strong', $this->nextLabel, [
            'class' => 'text-primary'
        ]) . Html::tag('h6', $this->nextTitle), [
            'class' => 'text'
        ]) . Html::tag('div', Html::tag('i', '', [
            'class' => $this->nextIcon
        ]), [
            'class' => 'icon next'
        ]);
    }

    function getNextOrPrevId($nextOrPrev)
    {
        $records = NULL;
        if ($nextOrPrev == self::PREV) {
            $where = [
                '<',
                'id',
                $this->model->id
            ];
            $order = [
                'id' => SORT_DESC
            ];
        }
        if ($nextOrPrev == self::NEXT) {
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
            'id',
            'title'
        ])
            ->where($where)
            ->orderBy($order)
            ->one();
        if (! empty($records)) {
            return $records;
        }
        /*
         * foreach ($records as $i => $r)
         * if ($r->id == $currentId)
         * return isset($records[$i + 1]->id) ? $records[$i + 1]->id : NULL;
         */

        return NULL;
    }
}
