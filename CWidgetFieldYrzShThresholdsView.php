<?php

namespace Modules\YrzStatusHistory;

use Modules\YrzStatusHistory\CWidgetFieldYrzShThresholds;
use \CWidgetFieldThresholdsView, \CDiv, \CTable, \CColHeader, \CRow, \CCol, \CButtonLink, \CColor, \CTextBox, \CButton;

class CWidgetFieldYrzShThresholdsView extends \CWidgetFieldThresholdsView {

  public function getView(): CDiv {
		$thresholds_table = (new CTable())
			->setId($this->field->getName().'-table')
			->addClass(ZBX_STYLE_TABLE_FORMS)
			->setHeader([
				'',
				(new CColHeader(_('Threshold'))),
        (new CColHeader(_('Text').' ('._('Optional').')'))
          ->setWidth('100%'),
				_('Action')
			])
			->setFooter(new CRow(
				new CCol(
					(new CButtonLink(_('Add')))->addClass('element-table-add')
				)
			));

		foreach ($this->field->getValue() as $i => $threshold) {
      if (!isset($threshold['text'])) $threshold['text'] = '';
			$thresholds_table->addRow(
				$this->getRowTemplate($i, $threshold['color'], $threshold['threshold'], $threshold['text'])
			);
		}

		return (new CDiv($thresholds_table))->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH);
	}


  public function getRowTemplate($row_num = '#{rowNum}', $color = '#{color}', $threshold = '#{threshold}', $text = '#{text}'): CRow {
		return (new CRow([
			(new CColor($this->field->getName().'['.$row_num.'][color]', $color))->appendColorPickerJs(false),
			(new CTextBox($this->field->getName().'['.$row_num.'][threshold]', $threshold, false))
				->setWidth(ZBX_TEXTAREA_TINY_WIDTH)
				->setAriaRequired(),
      (new CTextBox($this->field->getName().'['.$row_num.'][text]', $text, false))
        ->setWidth(ZBX_TEXTAREA_SMALL_WIDTH),
			(new CButton($this->field->getName().'['.$row_num.'][remove]', _('Remove')))
				->addClass(ZBX_STYLE_BTN_LINK)
				->addClass('element-table-remove')
		]))->addClass('form_row');
	}

}