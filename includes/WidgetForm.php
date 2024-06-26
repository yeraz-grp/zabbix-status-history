<?php

namespace Modules\YrzStatusHistory\Includes;

use Zabbix\Widgets\{
  CWidgetField,
  CWidgetForm
};

use Zabbix\Widgets\Fields\{
  CWidgetFieldCheckBox,
  CWidgetFieldColor,
  CWidgetFieldIntegerBox,
  CWidgetFieldMultiSelectItem,
  CWidgetFieldRadioButtonList,
  CWidgetFieldSelect
};

use Modules\YrzStatusHistory\{
  Widget,
  CWidgetFieldYrzShThresholds
};

error_reporting(E_ALL);
ini_set("display_errors", 1);
// die('ded');

class WidgetForm extends CWidgetForm {

  private const DEFAULT_NUM_DAYS = 7;
  private const DEFAULT_BASE_COLOR = '202020';
  private const DEFAULT_CELL_WIDTH = 20;
  private const DEFAULT_CELL_HEIGHT = 20;
  private const DEFAULT_GAP_HORIZONTAL = 2;
  private const DEFAULT_GAP_VERTICAL = 2;
  private const DEFAULT_LABEL_WIDTH = 0;
  private const DEFAULT_VALUE_ROUND = 2;

  public function addFields(): self {
    return $this
      ->addField(
        (new CWidgetFieldMultiSelectItem('itemid', _('Items')))
          ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
      )
      ->addField(
        (new CWidgetFieldIntegerBox('num_days', _('Number of days')))
          ->setDefault(self::DEFAULT_NUM_DAYS)
          ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
      )
      ->addField(
        (new CWidgetFieldSelect('agg_func', _('Aggregation function'), [
          Widget::YRZ_SH_AGG_FUNC_FIRST => _('first'),
          Widget::YRZ_SH_AGG_FUNC_LAST => _('last'),
          Widget::YRZ_SH_AGG_FUNC_MIN => _('min'),
          Widget::YRZ_SH_AGG_FUNC_MAX => _('max'),
          Widget::YRZ_SH_AGG_FUNC_AVG => _('avg')
        ]))
          ->setDefault(Widget::YRZ_SH_AGG_FUNC_LAST)
      )
      ->addField(
				(new CWidgetFieldColor('base_color', _('Base color')))
					->setDefault(self::DEFAULT_BASE_COLOR)
			)
      ->addField(
        new CWidgetFieldYrzShThresholds('thresholds', _('Thresholds'))
      )
      ->addField(
        (new CWidgetFieldIntegerBox('cell_width', _('Cells width')))
          ->setDefault(self::DEFAULT_CELL_WIDTH)
          ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
      )
      ->addField(
        (new CWidgetFieldIntegerBox('cell_height', _('Cells height')))
          ->setDefault(self::DEFAULT_CELL_HEIGHT)
          ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
      )
      ->addField(
        (new CWidgetFieldIntegerBox('gap_horizontal', _('Horizontal gap')))
          ->setDefault(self::DEFAULT_GAP_HORIZONTAL)
          ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
      )
      ->addField(
        (new CWidgetFieldIntegerBox('gap_vertical', _('Vertical gap')))
          ->setDefault(self::DEFAULT_GAP_VERTICAL)
          ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
      )
      ->addField(
        (new CWidgetFieldCheckBox('show_label', _('Show')))
          ->setDefault(Widget::YRZ_SH_ON)
      )
      ->addField(
        (new CWidgetFieldRadioButtonList('label_align', _('Alignment'), [
          Widget::YRZ_SH_LABEL_ALIGN_LEFT => _('Left'),
          Widget::YRZ_SH_LABEL_ALIGN_RIGHT => _('Right')
        ]))
          ->setDefault(Widget::YRZ_SH_LABEL_ALIGN_RIGHT)
      )
      ->addField(
        (new CWidgetFieldIntegerBox('label_width', _('Width')))
          ->setDefault(self::DEFAULT_LABEL_WIDTH)
      )
      ->addField(
        (new CWidgetFieldRadioButtonList('cell_position', _('Position'), [
          Widget::YRZ_SH_CELL_POSITION_LEFT => _('Left'),
          Widget::YRZ_SH_CELL_POSITION_RIGHT => _('Right')
        ]))
          ->setDefault(Widget::YRZ_SH_CELL_POSITION_LEFT)
      )
      ->addField(
        (new CWidgetFieldRadioButtonList('cell_order', _('Order'), [
          Widget::YRZ_SH_CELL_ORDER_NEWEST => _('Newest first'),
          Widget::YRZ_SH_CELL_ORDER_OLDEST => _('Oldest first')
        ]))
          ->setDefault(Widget::YRZ_SH_CELL_ORDER_OLDEST)
      )
      ->addField(
        (new CWidgetFieldCheckBox('show_value', _('Show value')))
          ->setDefault(Widget::YRZ_SH_OFF)
      )
      ->addField(
        (new CWidgetFieldIntegerBox('value_round', _('Round value')))
          ->setDefault(self::DEFAULT_VALUE_ROUND)
      )
      ->addField(
        (new CWidgetFieldRadioButtonList('cell_align', _('Alignment'), [
          Widget::YRZ_SH_CELL_ALIGN_LEFT => _('Left'),
          Widget::YRZ_SH_CELL_ALIGN_CENTER => _('Center'),
          Widget::YRZ_SH_CELL_ALIGN_RIGHT => _('Right')
        ]))
          ->setDefault(Widget::YRZ_SH_CELL_ALIGN_CENTER)
      )
      ->addField(
        (new CWidgetFieldCheckBox('show_date', _('Show timestamp')))
          ->setDefault(Widget::YRZ_SH_ON)
      )
      ->addField(
        (new CWidgetFieldRadioButtonList('date_format', _('Date format'), [
          Widget::YRZ_SH_DATE_FORMAT_ALL => _('All'),
          Widget::YRZ_SH_DATE_FORMAT_WEEKLY => _('Weekly')
        ]))
          ->setDefault(Widget::YRZ_SH_DATE_FORMAT_WEEKLY)
      )
      ->addField(
        (new CWidgetFieldCheckBox('color_interval', _('Threshold interval')))
          ->setDefault(Widget::YRZ_SH_ON)
      )
      ->addField(
        (new CWidgetFieldCheckBox('color_interpolation', _('Color interpolation')))
          ->setDefault(Widget::YRZ_SH_OFF)
      )
      ->addField(
        (new CWidgetFieldCheckBox('color_exceed', _('Exceed min/max threshold')))
          ->setDefault(Widget::YRZ_SH_OFF)
      )
      ->addField(
        (new CWidgetFieldCheckBox('show_legend', _('Show')))
          ->setDefault(Widget::YRZ_SH_OFF)
      )
      ->addField(
        (new CWidgetFieldRadioButtonList('legend_layout', _('Layout'), [
          Widget::YRZ_SH_LEGEND_LAYOUT_TABLE => _('Table'),
          Widget::YRZ_SH_LEGEND_LAYOUT_LIST => _('List')
        ]))
          ->setDefault(Widget::YRZ_SH_LEGEND_LAYOUT_TABLE)
      );
  }
}
