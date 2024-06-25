<?php

use Modules\YrzStatusHistory\Widget;

error_reporting(E_ALL);
ini_set("display_errors", 1);
//die('ded');

# Setting values
$cellWidth = $data['cell_width'].'px';
$cellHeight = $data['cell_height'].'px';
$gap = $data['gap_horizontal'].'px '.$data['gap_vertical'].'px';

$labelAlign = $data['label_align'] == Widget::YRZ_SH_LABEL_ALIGN_LEFT ? 'start' : 'end';
$labelWidth = $data['label_width'] > 0 ? $data['label_width'].'px' : 'minmax(min-content, max-content)';

$cellPosition = $data['cell_position'] == Widget::YRZ_SH_CELL_POSITION_LEFT ? 'start' : 'space-between';
$cellOrder = $data['cell_order'] == Widget::YRZ_SH_CELL_ORDER_NEWEST ? 'ltr' : 'rtl';
$cellAlign = $data['cell_align'] == Widget::YRZ_SH_CELL_ALIGN_LEFT ? 'start' : (
  $data['cell_align'] == Widget::YRZ_SH_CELL_ALIGN_RIGHT ? 'end' : 'center'
);

$legendLayout = $data['legend_layout'] == Widget::YRZ_SH_LEGEND_LAYOUT_LIST ? 'row' : 'column';

# Create view
$view = new CWidgetView($data);

# Create element containers
$mainContainer = (new CDiv())
  ->addClass('main-container')
  ->addStyle('justify-content: '.$cellPosition);

$labelContainer = (new CDiv())
  ->addClass('label-container')
  ->addStyle('grid-auto-rows: '.$cellHeight.'; grid-template-columns: '.
  $labelWidth.'; gap: '.$gap.';'
  );
$historyContainer = (new CDiv())
  ->addClass('history-container')
  ->addStyle('grid-template-columns: repeat('.$data['num_days'].', '.
  $cellWidth.'); gap: '.$gap.'; direction: '.$cellOrder.';'
  );
$legendContainer = (new CDiv())
  ->addClass('legend-container')
  ->addStyle('flex-direction: '.$legendLayout);

  
foreach ($data['items'] as $item) {

  # Label
  if ($data['show_label'] == Widget::YRZ_SH_ON) {
    $labelContainer->addItem(
      (new CDiv(
        (new CSpan($item['name']))
      ))
        ->addClass('label-item')
        ->addStyle('justify-content: '.$labelAlign.';')
    );
  }

  # History status
  for ($i = 0; $i < $data['num_days']; $i++) {
    $columnDate = date('Y-m-d', time() - $i * (60 * 60 * 24));
    $statusValue = $data['show_value'] == Widget::YRZ_SH_ON ? 'N/A' : '';
    $statusColor = $data['base_color'];

    foreach ($item as $itemStatus) {
      if (isset($itemStatus['day'])) {
        if ($itemStatus['day'] == $columnDate) {
          if ($data['show_value'] == Widget::YRZ_SH_ON) {
            $statusValue = round(floatval($itemStatus['value']), $data['value_round']);
          }
          $lastThreshold = null;
          foreach ($data['thresholds'] as $threshold) {
            if ($itemStatus['value'] == $threshold['threshold'] || (
            $data['color_exceed'] == Widget::YRZ_SH_ON AND
            $lastThreshold === null && $itemStatus['value'] < $threshold['threshold']
            )) {
              $statusColor = $threshold['color'];
            }
            else if ($data['color_interval'] == Widget::YRZ_SH_ON AND $lastThreshold !== null AND
            $lastThreshold['threshold'] < $itemStatus['value'] &&
            $itemStatus['value'] < $threshold['threshold']) {
              if ($data['color_interpolation'] == Widget::YRZ_SH_ON) {
                $statusColor = color_interpolation(
                  $lastThreshold['color'], $threshold['color'], $lastThreshold['threshold'],
                  $threshold['threshold'], $itemStatus['value']
                );
              }
              else {
                $statusColor = $lastThreshold['color'];
              }
            }
            $lastThreshold = $threshold;
          }
        }
      }
    }

    $historyContainer->addItem(
      (new CDiv(
        (new CSpan($statusValue))
      ))
        ->addClass('history-item')
        ->addStyle('height: '.$cellHeight.'; justify-content: '.$cellAlign.'; background-color: #'.$statusColor.';')
    );
  }
}

# Date (if activated)
if ($data['show_date'] == Widget::YRZ_SH_ON) {
  for ($i = 0; $i < $data['num_days']; $i++) {
    $dateOfTheDay = date('d/m', time() - $i * (60 * 60 * 24));
    $dayOfTheWeek = date('D', time() - $i * (60 * 60 * 24));

    $dateToShow = '';
    if ($data['date_format'] == Widget::YRZ_SH_DATE_FORMAT_ALL || $dayOfTheWeek == 'Mon') {
      $dateToShow = [
        new CTag('b', true, _($dayOfTheWeek)),
        NBSP(),
        $dateOfTheDay
      ];
    }

    $historyContainer->addItem(
      (new CDiv($dateToShow))
        ->addClass('date-item')
    );
  }
}

# Legend
foreach ($data['thresholds'] as $threshold) {
  $legendItem = (new CDiv())
    ->addClass("legend-item");

  $legendItem->addItem(
    (new CDiv())
      ->addClass('legend-color')
      ->addStyle('background-color: #'.$threshold['color'])
  );
  $legendItem->addItem(
    (new CDiv($threshold['threshold']))
      ->addClass('legend-text')
  );

  $legendContainer->addItem($legendItem);
}


# Show result to view
if ($data['show_label'] == Widget::YRZ_SH_ON) $mainContainer->addItem($labelContainer);
$mainContainer->addItem($historyContainer);
$view->addItem($mainContainer);

if ($data['show_legend'] == Widget::YRZ_SH_ON) $view->addItem($legendContainer);

$view->show();


# Color interpolation functions
function color_interpolation($color1, $color2, $threshold1, $threshold2, $value) {
  $r = round(interpolation_value(
    hexdec(substr($color1, 0, 2)), hexdec(substr($color2, 0, 2)),
    $threshold1, $threshold2, $value
  ));
  $g = round(interpolation_value(
    hexdec(substr($color1, 2, 2)), hexdec(substr($color2, 2, 2)),
    $threshold1, $threshold2, $value
  ));
  $b = round(interpolation_value(
    hexdec(substr($color1, 4, 2)), hexdec(substr($color2, 4, 2)),
    $threshold1, $threshold2, $value
  ));

  return sprintf("%02x%02x%02x", $r, $g, $b);
}
function interpolation_value($y1, $y2, $x1, $x2, $x) {
  $a = ($y1 - $y2) / ($x1 - $x2);
  $b = $y1 - $x1 * $a;
  return $a * $x + $b;
}