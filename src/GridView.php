<?php

namespace voskobovich\grid\advanced;

use himiklab\sortablegrid\SortableGridAsset;
use himiklab\sortablegrid\SortableGridView;
use nterms\pagesize\PageSize;
use voskobovich\grid\advanced\plugins\shifty\ShiftyAsset;
use Yii;
use yii\grid\CheckboxColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;


/**
 * Class GridView
 * @package voskobovich\grid\advanced
 */
class GridView extends SortableGridView
{
    /**
     * Включить сортировку записей перетаскиванием
     * @var bool
     */
    public $isDraggable = false;

    /**
     * Включить мульти селектор для чекбоксов
     * @var bool
     */
    public $enableShiftyPlugin = true;

    /**
     * Выбор количества на страницу
     * @var string
     */
    public $filterSelector = 'select[name="per-page"]';

    /**
     * Кастомные кнопки
     * @var array
     */
    public $customButtons = [];

    /**
     * Список действий над элементами
     * @var array
     */
    public $actionList = [
        'actions' => [
            'delete' => 'Delete',
        ],
        'with' => [
            'selected' => 'Selected',
            'all' => 'All',
        ],
        'withLabel' => 'With',
        'actionsLabel' => 'Action',
        'submitLabel' => 'Proceed',
    ];

    /**
     * Массив для выпадающего списка постраничной разбивки
     * @var array
     */
    public $sizes = [25 => 25, 50 => 50, 100 => 100, 200 => 200];

    /**
     * Настройка пагинатора
     * @var array
     */
    public $pager = [
        'class' => 'justinvoelker\separatedpager\LinkPager',
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
        'hideOnSinglePage' => true,
    ];

    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{errors}`: the filter model error summary. See [[renderErrors()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = '
        <div class="box">
            <div class="box-header">
                {summary}&nbsp;&nbsp;&nbsp;{customButtons}
            </div>
            <div class="form-inline" role="grid">
                {items}
                <div class="box-footer">
                    <div class="width50per text-left">
                        {actionList}
                    </div><div class="width50per text-right">
                        {pageSizeWidget}
                        {pager}
                    </div>
                </div>
            </div>
        </div>
    ';

    /**
     * Action handler route
     * @var string
     */
    public $handlerRoute = 'grid-handler';

    /**
     * @inheritdoc
     */
    public $dataColumnClass = 'voskobovich\grid\advanced\columns\DataColumn';

    /**
     * Инициализация
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        GridViewAsset::register($this->view);

        if (is_array($this->actionList) && !empty($this->actionList)) {
            $this->registerCheckboxes();
        }

        if (is_array($this->customButtons) && empty($this->customButtons)) {
            $this->customButtons[] = [
                Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Create'),
                ['create'],
                ['class' => 'btn btn-default btn-xs btn-flat', 'data-pjax' => '0']
            ];
        }

        parent::init();

        if ($this->isDraggable) {
            $this->disableGridSorting();
        }
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case "{customButtons}":
                return $this->renderCustomButtons();
            case "{actionList}":
                return $this->renderActionList();
            case "{pageSizeWidget}":
                return $this->renderPageSizeWidget();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Блок пагинации
     * @return string
     */
    public function renderPager()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }

        /* @var $class LinkPager */
        $linkPager = $this->pager;
        $linkPager['pagination'] = $pagination;
        $linkPager['view'] = $this->getView();
        $linkPager['options'] = [
            'class' => 'pagination pagination-sm'
        ];

        $class = ArrayHelper::remove($linkPager, 'class', LinkPager::className());
        return $class::widget($linkPager);
    }

    /**
     * Renders validator errors of filter model.
     * @return string the rendering result.
     */
    public function renderActionList()
    {
        if (is_array($this->actionList) && !empty($this->actionList)) {

            $withDropDownList = Html::dropDownList(
                'with',
                null,
                $this->actionList['with'],
                [
                    'prompt' => Yii::t(
                        'vendor/voskobovich/yii2-advanced-grid/interface/common',
                        $this->actionList['withLabel']
                    ),
                    'class' => 'form-control'
                ]
            );

            $actionsDropDownList = Html::dropDownList(
                'action',
                null,
                $this->actionList['actions'],
                [
                    'prompt' => Yii::t(
                        'vendor/voskobovich/yii2-advanced-grid/interface/common',
                        $this->actionList['actionsLabel']
                    ),
                    'class' => 'form-control'
                ]
            );

            $submitButton = Html::submitButton(
                Yii::t(
                    'vendor/voskobovich/yii2-advanced-grid/interface/common',
                    $this->actionList['submitLabel']
                ),
                [
                    'class' => 'btn btn-sm btn-default',
                    'data-confirm' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Are you sure?')
                ]
            );

            $withDropDownCol = Html::tag('div', $withDropDownList, ['class' => 'input-group input-group-sm']);
            $actionDropDownCol = Html::tag('div', $actionsDropDownList, ['class' => 'input-group input-group-sm']);

            return $withDropDownCol . '&nbsp;' . $actionDropDownCol . '&nbsp;' . $submitButton;
        }

        return '';
    }

    /**
     * Renders validator errors of filter model.
     * @return string the rendering result.
     */
    public function renderCustomButtons()
    {
        $return = '';

        if (is_array($this->customButtons)) {
            foreach ($this->customButtons as $customButton) {
                $customButtonText = !empty($customButton[0]) ? $customButton[0] : '';
                $customButtonUrl = !empty($customButton[1]) ? $customButton[1] : '#';
                $customButtonOptions = !empty($customButton[2]) ? $customButton[2] : [];

                $return .= Html::a($customButtonText, $customButtonUrl, $customButtonOptions);
            }
        }

        return $return;
    }

    /**
     * Renders validator errors of filter model.
     * @return string the rendering result.
     */
    public function renderPageSizeWidget()
    {
        if (!empty($this->sizes)) {
            $sizesKeys = array_keys($this->sizes);

            return PageSize::widget([
                'encodeLabel' => false,
                'label' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Size:') . '&nbsp;',
                'labelOptions' => [
                    'class' => 'control-label',
                    'style' => 'font-weight: normal'
                ],
                'template' => Html::tag('div', '{label}{list}',
                    ['class' => 'form-group']),
                'options' => [
                    'class' => 'form-control input-sm',
                ],
                'defaultPageSize' => $sizesKeys[0],
                'sizes' => $this->sizes,
            ]);
        }

        return '';
    }

    /**
     * Renders the summary text.
     */
    public function renderSummary()
    {
        $summary = parent::renderSummary();

        return !empty($summary) ? Html::tag('h3', $summary, ['class' => 'box-title']) : '';
    }

    /**
     * Регистрация скриптов для сортировки
     */
    protected function registerWidget()
    {
        if ($this->isDraggable) {
            $this->rowOptions['style'] = 'cursor: move;';
            $view = $this->getView();
            $view->registerJs("jQuery('#{$this->id}').SortableGridView('{$this->sortableAction}');");
            SortableGridAsset::register($view);
        }
    }

    /**
     * Убирает сортировку по колонкам в gridview
     */
    protected function disableGridSorting()
    {
        foreach ($this->columns as $column) {
            if (isset($column->enableSorting)) {
                $column->enableSorting = false;
            }
        }
    }

    /**
     * Для групповых действий добавляем чекбоксы к строкам
     */
    protected function registerCheckboxes()
    {
        $this->layout = Html::beginForm([$this->handlerRoute], 'post') . $this->layout . Html::endForm();

        $this->columns = ArrayHelper::merge([
            [
                'class' => CheckboxColumn::className(),
                'options' => [
                    'width' => '30px'
                ],
            ],
        ], $this->columns);

        if ($this->enableShiftyPlugin) {
            $this->registerShiftyPlugin();
        }
    }

    /**
     * Включение плагина shifty для мультиселекта
     */
    protected function registerShiftyPlugin()
    {
        $view = $this->view;
        ShiftyAsset::register($view);
        $view->registerJs("
            $('#{$this->id} tbody tr').shifty({
                className: 'select',
                select: function(el) {
                    el.find('[type=checkbox]').prop('checked',true);
                },
                unselect: function(el) {
                    el.find('[type=checkbox]').prop('checked',false);
                }
            });
        ");
    }
}