<?php

namespace Modules\YrzStatusHistory\Actions;

use API,
  CControllerDashboardWidgetView,
  CControllerResponseData;

use Modules\YrzStatusHistory\Widget;

class WidgetView extends CControllerDashboardWidgetView {

  protected function doAction(): void {
    # Get items from hosts
    $dbItems = API::Item()->get([
      'output' => ['itemid', 'value_type', 'name', 'units'],
      'itemids' => $this->fields_values['itemid'],
      'webitems' => true,
      'filter' => [
        'value_type' => [ITEM_VALUE_TYPE_UINT64, ITEM_VALUE_TYPE_FLOAT]
      ]
    ]);

    $tables = [ 
      0 => 'history',
      3 => 'history_uint',
    ];

    # SQL Query for getting the last status for each day
    if ($dbItems) {
      $values = array();

      foreach ($dbItems as $item) {

        $dbQuery = null;

        if (!isset($tables[$item['value_type']])) continue;

        if ($this->fields_values['agg_func'] == Widget::YRZ_SH_AGG_FUNC_FIRST ||
        $this->fields_values['agg_func'] == Widget::YRZ_SH_AGG_FUNC_LAST) {
          $orderBy = $this->fields_values['agg_func'] == Widget::YRZ_SH_AGG_FUNC_FIRST ? 'ASC' : 'DESC';
          $dbQuery = DBselect(
            'SELECT value, CAST(FROM_UNIXTIME(clock) AS date) AS day'.
            ' FROM ('.
              ' SELECT value, clock,'.
                ' ROW_NUMBER() OVER (PARTITION BY DATE(FROM_UNIXTIME(clock)) ORDER BY clock '.$orderBy.') AS rn'.
              ' FROM '.$tables[$item['value_type']].
              ' WHERE itemid = '.$item['itemid'].
              ' ) sub'.
            ' WHERE rn = 1'.
            ' ORDER BY FROM_UNIXTIME(clock) DESC;'
          );
        }
        else {
          $queryType = $this->fields_values['agg_func'] == Widget::YRZ_SH_AGG_FUNC_MIN ? 'MIN(value)' :
          ($this->fields_values['agg_func'] == Widget::YRZ_SH_AGG_FUNC_MAX ? 'MAX(value)' : 'AVG(value)');
          $dbQuery = DBselect(
            'SELECT '.$queryType.' AS value, CAST(FROM_UNIXTIME(clock) AS date) AS day'.
            ' FROM '.$tables[$item['value_type']].
            ' WHERE itemid = '.$item['itemid'].
            ' GROUP BY CAST(FROM_UNIXTIME(clock) AS date)'.
            ' ORDER BY CAST(FROM_UNIXTIME(clock) AS date) DESC;'
          );
        }

        $itemHistory = array();
        while ($dbResult = DBfetch($dbQuery)) {
          $itemHistory[] = $dbResult;
        }
        $itemHistory['name'] = $item['name'];

        $values[] = $itemHistory;
      }
    }

    # Send result into the view form
    $this->setResponse(new CControllerResponseData([
      'name' => $this->getInput('name', $this->widget->getName()),
      'items' => $values,
      'num_days' => $this->fields_values['num_days'],
      'base_color' => $this->fields_values['base_color'],
      'thresholds' => $this->fields_values['thresholds'],
      'cell_width' => $this->fields_values['cell_width'],
      'cell_height' => $this->fields_values['cell_height'],
      'gap_horizontal' => $this->fields_values['gap_horizontal'],
      'gap_vertical' => $this->fields_values['gap_vertical'],
      'show_label' => $this->fields_values['show_label'],
      'label_align' => $this->fields_values['label_align'],
      'label_width' => $this->fields_values['label_width'],
      'cell_position' => $this->fields_values['cell_position'],
      'cell_order' => $this->fields_values['cell_order'],
      'show_value' => $this->fields_values['show_value'],
      'value_round' => $this->fields_values['value_round'],
      'cell_align' => $this->fields_values['cell_align'],
      'show_date' => $this->fields_values['show_date'],
      'date_format' => $this->fields_values['date_format'],
      'color_interval' => $this->fields_values['color_interval'],
      'color_interpolation' => $this->fields_values['color_interpolation'],
      'color_exceed' => $this->fields_values['color_exceed'],
      'show_legend' => $this->fields_values['show_legend'],
      'legend_layout' => $this->fields_values['legend_layout'],
      'user' => [
        'debug_mode' => $this->getDebugMode()
      ]
    ]));
  }

}
